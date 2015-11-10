<?php
//include
require_once('config/config.php');
require_once('lib/class-db.php');
require_once('lib/class-table.php');
require_once('lib/class-record.php');

// $class_name = $_GET['class_name'];

$table = new Table($DBInfo);

//Add Product
$record = new Record('Product');
$record->setValue('Name');
$record->setMatchValue('Name', '1');
$res = $table->getRecord($record);

echo "<br>" . $res['msg'] . print_r($res);




?>