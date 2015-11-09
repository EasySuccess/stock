<?php
//include
require_once('config/config.php');
require_once('lib/DB.class.php');
require_once('lib/Table.class.php');
require_once('lib/Record.class.php');

// $class_name = $_GET['class_name'];

$table = new Table($DBInfo);

//Add Product
$record = new Record("Product");
$record->setValue("Name", "美的");
$record->setValue("Cost", 20);
$record->setValue("Price", 200);
$record->setValue("Description", "2");
$res = $table->addRecord($record);

echo $res['msg'];




?>