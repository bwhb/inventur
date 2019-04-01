<?php
ini_set('memory_limit', '1024M'); // or you could use 1G

require __DIR__ . '/vendor/autoload.php';


use pear\file_marc\File;
use  PHPOnCouch\CouchClient; //The CouchDB client object

$client = new CouchClient('http://127.0.0.1:5984', 'marc21');
$client2 = new CouchClient('http://127.0.0.1:5984', 'marc22');

$journals = new File_MARC('data/marc21/051-tit.mrc');
$os = [];
$dos = [];
$ppns = json_decode(file_get_contents("ppns.json"));
while ($record = $journals->next()) {
    $ppn = preg_replace("/\d{3}\s+(.*)/","$1",$record->getField('001', true)->__toString());
    if(in_array($ppn,$ppns)){
		$i++;
		//echo $record->__toString();
		unset ($o);
		if($record->getField('245'))$o->titel = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('245', true)->getSubfield('a'));
		if($record->getField('100'))$o->aut  = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('100')->getSubfield('a'));
		if($record->getField('250'))$o->aufl = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('250', true)->getSubfield('a'));
		if($record->getField('260'))$o->ort = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('260', true)->getSubfield('a'));
		if($record->getField('260'))$o->jahr = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('260', true)->getSubfield('c'));
		$lok = $client->getDoc("A$ppn");
		$o->sig = $lok->sig;
		$o->tb = $lok->tb;
		$o->tbkz = $lok->tbkz;
		//$client->deleteDoc("A$ppn");

		/*
		print_r($o);
		die ();
		$o =json_decode(Zend\Xml2Json\Xml2Json::fromXml($record->toXML(), true));
		*/
    	$o->checked = false;
    	$o->counter = 0;
    	$o->_id = preg_replace("/\d{3}\s+(.*)/","$1",$record->getField('001', true)->__toString());
    	if($o->jahr<1946)array_push($os, $o);
    	else array_push($dos, $o);
    	//array_push($dos, $lok);
    	if($i==1000){
			$client2->storeDocs($os);
			//$client->deleteDocs($dos);
			$os = [];
			$i =0;
		}
	}

}
$client2->storeDocs($os);
//$client->deleteDocs($dos);
//file_put_contents("ppns.json", json_encode($ppns))
?>