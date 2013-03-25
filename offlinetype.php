<?php
error_reporting(0);

include 'mysqlconnect.php';
$params = array(
    'hostname' => 'localhost',
    'dbname' => 'mdjyi',
    'username' => 'root',
    'password' => ''
);
$connect = new mysqlconnect($params);
$connect->connect();

$type = $connect->fetch('select * from info_type');

$backdata = array(
	'type' => $type
);
$backdata = json_encode($backdata);
echo 'typecbfunc('.$backdata.')';
?>
