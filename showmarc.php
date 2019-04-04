<?php
ini_set('memory_limit', '1024M'); // or you could use 1G
#ini_set('display_errors','Off');
ini_set('error_reporting', 0 );

require __DIR__ . '/vendor/autoload.php';


use pear\file_marc\File;
$journals = new File_MARC('data/marc21/051-tit.mrc');
$os = [];
$ppns = [];
while ($record = $journals->next()) {
	echo $record->__toString();	
}
?>