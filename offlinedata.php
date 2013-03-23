<?php
error_reporting(0);
$PAGEMAX = 100;

include 'mysqlconnect.php';
$params = array(
    'hostname' => 'localhost',
    'dbname' => 'mdjyi',
    'username' => 'root',
    'password' => ''
);
$connect = new mysqlconnect($params);
$connect->connect();

$filter = '';
$typeid = intval($_GET['typeid']);
if ($typeid != 0)
    $filter = 'where type_id=' . $typeid;

$pager = intval($_GET['pager']);
$pager = $pager == 0 ? 1 : $pager;

$type = $connect->fetch('select * from info_type');

$data = $connect->fetch("select info.id,title,content,insert_time,type_name,info_level 
                            from info join info_type on info.type_id=info_type.id {$filter} order by info_level,insert_time desc,id desc limit ?,?", array(($pager - 1) * $PAGEMAX, $PAGEMAX));
$infonum = $connect->fetch("select count(info.id) num from info join info_type on info.type_id=info_type.id {$filter}");
$infonum = $infonum[0]['num'];

$pagenum = round(0.4999 + $infonum / $PAGEMAX);

$backdata = array(
	'data'=> $data,
	'infonum' => $infonum,
	'pagenum' => $pagenum
);
$backdata = json_encode($backdata);
echo 'datacbfunc('.$backdata.')';
?>
