<?php
ini_set('memory_limit', '1024M'); // or you could use 1G
#ini_set('display_errors','Off');
ini_set('error_reporting', 0 );

require __DIR__ . '/vendor/autoload.php';


use pear\file_marc\File;
use  PHPOnCouch\CouchClient; //The CouchDB client object

$client = new CouchClient('http://127.0.0.1:5984', 'marc21');
$journals = new File_MARC('data/marc21/051-lok.mrc');
$os = [];
$ppns = [];
echo "\nVerarbeite Lokaldaten";
while ($record = $journals->next()) {
	foreach($record->getFields('852') as $v) forEach($v->getSubfields("c") as $sig) //echo $sig."\n";
    if(preg_match("/preu|kultur/i",$record->getField('852', true)->getSubfield('z')) OR preg_match(!"/reichsgericht/i",$record->getField('852', true)->getSubfield('z')) OR preg_match("/^pr|^ol/i",$record->getField('935', true)) OR (!preg_match("/(par|ent|ads|zsn|nib|np|a 25|8\+|4\+|2\+)/i",$sig)&& !$record->getField('935', true))){
    //if((!empty($sig)&&!preg_match("/(par|ent|ads|zsn)/i",$sig) && !$record->getField('935', true))){
		//echo $sig;
		$i++;
		unset ($o);
		if ($record->getField('004', true)){
			$o->ppn = preg_replace("/\d{3}\s+(.*)/","$1",$record->getField('004', true)->__toString());
		}
		if ($record->getField('866', true)&&$record->getField('866', true)->getSubfield('a')){
			$o->seq = preg_replace("/.*?_a(.*?)_?.*?/","$1",$record->getField('866')->__toString());
			$o->seq = preg_replace("/(.*?)_.*/","$1",$o->seq);
		}

		$o->sig =preg_replace("/\[c\]:\s(.*)/","$1",$sig);  
		$o->sig =preg_replace("/^[\-\#\+]*(.*)/","$1",$o->sig);  
		  
		while(preg_match("/(.*\b)(\d{1,3})(\b.*)/",$o->sig)){
			$o->sig =preg_replace_callback("/(.*\b)(\d{1,3})(\b.*)/",function($t){
				$t[2]= str_pad($t[2], 4, "0", STR_PAD_LEFT);
				return $t[1].$t[2].$t[3];
			},$o->sig);
		}  
		$o->sig =preg_replace("/^0+([1-9])/","$1",$o->sig);  
		//preg_replace("/\d{3}\s+1 _c([^\n]*?)\n.*/","$1",$record->getFields('852',true)[2]);
		$o->tb = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('852', true)->getSubfield('z'));
		if ($record->getField('935', true))$o->tbkz = preg_replace("/\d{3}\s+_a(.{2}).*/","$1",$record->getField('935', true));
		if(preg_match("/preu/i",$record->getField('852', true)->getSubfield('z')))$o->tbkz = "pr";
		if(preg_match("/kultur/i",$record->getField('852', true)->getSubfield('z')))$o->tbkz = "ol";
		if (!$o->tbkz or empty($o->tbkz)) {
			$o->tbkz = "pr";

		}
		//print_r($o);

		//if ($o->seq)die($record->__toString());
		/*
		//$o =json_decode(Zend\Xml2Json\Xml2Json::fromXml($record->toXML(), true));
    	*/
    	$o->_id = preg_replace("/\d{3}\s+(.*)/","A$1",$record->getField('004', true)->__toString());
    	$ppns[] = preg_replace("/\d{3}\s+(.*)/","$1",$record->getField('004', true)->__toString());
    	array_push($os, $o);
    	if($i==1000){
    		echo ".";
			$client->storeDocs($os);
			$os = [];
			$i =0;
		}
	}

}
$client->storeDocs($os);
file_put_contents("ppns.json", json_encode($ppns))
?>