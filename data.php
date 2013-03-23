<?php
    include 'mysqlconnect.php';
    $params = array(
        'hostname' => '127.0.0.1',
        'dbname' => 'mdjyi',
        'username' => 'root',
        'password' => ''
    );
    $connect = new mysqlconnect($params);
    $connect->connect();
    $data  = urldecode($_POST['data']);
	$data = json_decode($data,true);
	for($i=0;$i<count($data);$i++)
	{
		$block = $connect->fetch('select title,content,insert_time from info where id=?',$data[$i]['id']);
		if (!count($block))
			$connect->insert('info',$data[$i]);
		else {
			if ($data[$i]['insert_time'] != $block[0]['insert_time'])
				$connect->update('info',$data[$i],array('id'=>$data[$i]['id']));
		}
	}
	//file_put_contents('/tmp/data.json',print_r($data,true));
?>
