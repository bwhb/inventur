<?php
ini_set('memory_limit', '1024M'); // or you could use 1G
ini_set('error_reporting', 0 );

require __DIR__ . '/vendor/autoload.php';
##curl -X DELETE http://127.0.0.1:5984/marc21 && curl -X PUT http://127.0.0.1:5984/marc21 && curl -X DELETE http://127.0.0.1:5984/marc22 && curl -X PUT http://127.0.0.1:5984/marc22  && curl -X DELETE http://127.0.0.1:5984/ol && curl -X PUT http://127.0.0.1:5984/ol && curl -X DELETE http://127.0.0.1:5984/pr && curl -X PUT http://127.0.0.1:5984/pr && php addlok.php && php addtit.php


use pear\file_marc\File;
use  PHPOnCouch\CouchClient; //The CouchDB client object

$client = new CouchClient('http://127.0.0.1:5984', 'marc21');
$client2 = new CouchClient('http://127.0.0.1:5984', 'marc22');
$col = new CouchClient('http://127.0.0.1:5984', 'ol');
$cpr = new CouchClient('http://127.0.0.1:5984', 'pr');

$journals = new File_MARC('data/marc21/051-tit.mrc');
$os = [];
$pros = [];
$olos = [];
$dos = [];
$ppns = json_decode(file_get_contents("ppns.json"));
echo "\nVerarbeite Titeldaten";
while ($record = $journals->next()) {
    $ppn = preg_replace("/\d{3}\s+(.*)/","$1",$record->getField('001', true)->__toString());
    if(in_array($ppn,$ppns)){
		$i++;
		//echo $record->__toString();
		unset ($o);
		if($record->getField('245')){
			$o->titel = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('245', true)->getSubfield('a'));
			if(preg_match(preg_replace("/(.*)\s*[\.;:\/]\s*$/",$o->titel)))$o->titel = preg_replace("/(.*)\s*[\.;:\/]\s*$/","$1",$o->titel);		
		}
		if($record->getField('100'))$o->aut  = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('100')->getSubfield('a'));
		if($record->getField('250'))$o->aufl = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('250', true)->getSubfield('a'));
		//if($record->getField('260'))$o->ort = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('260', true)->getSubfield('a'));
		if($record->getField('260'))$o->jahr = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('260', true)->getSubfield('c'));
		if($record->getField('362')){
			$o->seq = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('362', true)->getSubfield('a'));
		}
		
		if($record->getField('773')&&$record->getField('490')){
			$o->gtitel = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('490', true)->getSubfield('a'));
			$o->gtitel .= " ".preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('490', true)->getSubfield('v'));
			$o->gtitel .= " ".preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('773', true)->getSubfield('q'));
		}
		if($record->getField('773')&&$record->getField('245')){
			$o->gtitel = preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('245', true)->getSubfield('a'));
			$o->gtitel .= " ".preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('245', true)->getSubfield('n'));
			$o->gtitel .= " ".preg_replace("/\[\w\]:\s+(.*)/","$1",$record->getField('773', true)->getSubfield('q'));
		}
		$lok = $client->getDoc("A$ppn");
		$o->sig = $lok->sig;
		$o->seq = $lok->seq;
		//$o->tb = $lok->tb;
		$o->tbkz = $lok->tbkz;
		//$client->deleteDoc("A$ppn");

		/*
		print_r($o);
		die ();
		$o =json_decode(Zend\Xml2Json\Xml2Json::fromXml($record->toXML(), true));
		*/
    	$o->edited = false;
    	$o->recheck = false;
    	$o->checked = false;
    	$o->counter = 0;
    	$o->_id = preg_replace("/\d{3}\s+(.*)/","$1",$record->getField('001', true)->__toString());
    	if($o->jahr<1946){
    		array_push($os, $o);
    		if($o->tbkz=="pr")array_push($pros, $o);
    		if($o->tbkz=="ol")array_push($olos, $o);
    	}
    	if($i==1000){
    		echo ".";
			$client2->storeDocs($os);
			$cpr->storeDocs($pros);
			$col->storeDocs($olos);
			$os = [];
			$pros = [];
			$olos = [];
			$i =0;
		}
	}

}
$client2->storeDocs($os);
$cpr->storeDocs($pros);
$cos->storeDocs($olos);
?>