<?php
ini_set('memory_limit', '1024M'); // or you could use 1G

require __DIR__ . '/vendor/autoload.php';


use pear\file_marc\File;
use  PHPOnCouch\CouchClient; //The CouchDB client object

$client = new CouchClient('http://127.0.0.1:5984', 'marc21');

$journals = new File_MARC('data/marc21/051-lok.mrc');
$os = [];
$ppns = [];
while ($record = $journals->next()) {
    if(preg_match("/preu|kultur/i",$record->getField('852', true)->getSubfield('z')) OR preg_match("/^pr|^ol/i",$record->getField('935', true))){
		$i++;
		unset ($o);
		$o->ppn = preg_replace("/\d{3}\s+(.*)/","$1",$record->getField('004', true)->__toString());
		$o->sig = preg_replace("/\d{3}\s+1 _c([^\n]*?)\n.*/","$1",$record->getFields('852',true)[2]);
		$o->tb = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('852', true)->getSubfield('z'));
		$o->tbkz = preg_replace("/\d{3}\s+_a(.*)/","$1",$record->getField('935', true));

		/*
		print_r($o);
		die($record->__toString());
		//$o =json_decode(Zend\Xml2Json\Xml2Json::fromXml($record->toXML(), true));
    	*/
    	$o->_id = preg_replace("/\d{3}\s+(.*)/","A$1",$record->getField('004', true)->__toString());
    	$ppns[] = preg_replace("/\d{3}\s+(.*)/","$1",$record->getField('004', true)->__toString());
    	array_push($os, $o);
    	if($i==1000){
			$client->storeDocs($os);
			$os = [];
			$i =0;
		}
	}

}
$client->storeDocs($os);
file_put_contents("ppns.json", json_encode($ppns))
?>