<?php

class InfoModel
{
    private $conn = null;
    function __construct()
    {
        $this->conn = new mysqlconnect(Yaf_Registry::get('dbconfig'));
        $this->conn->connect();
    }
    public function fetchInfoType()
    {
        $sql = 'SELECT * FROM info_type';
        $result = $this->conn->fetch($sql);
        
        return $result;
    }
    
    public function fetchInfo($offset,$pagesize)
    {
        $sql = 'SELECT info.id,title,content,insert_time,type_name,info_level 
                            FROM info JOIN info_type ON info.type_id=info_type.id WHERE publish_status=1 
                                ORDER BY info_level,insert_time DESC,id DESC limit ?,?';
        $result = $this->conn->fetch($sql,array($offset,$pagesize));
        return $result;
    }
    public function fetchInfoNum()
    {
        $sql = 'SELECT COUNT(info.id) num FROM info JOIN info_type ON info.type_id=info_type.id WHERE publish_status=1';
        $result = $this->conn->fetch($sql);
        
        return isset($result[0]['num']) ? $result[0]['num'] : 0;
    }
    public function fetchInfoByTypeId($typeid,$offset,$pagesize)
    {
        $sql = 'SELECT info.id,title,content,insert_time,type_name,info_level 
                            FROM info JOIN info_type ON info.type_id=info_type.id WHERE publish_status=1 AND type_id = ? 
                                ORDER BY info_level,insert_time DESC,id DESC limit ?,?';
        $result = $this->conn->fetch($sql,array($typeid,$offset,$pagesize));
        return $result;
    }
    public function fetchInfoNumByTypeId($typeid)
    {
        $sql = 'SELECT COUNT(info.id) num FROM info JOIN info_type ON info.type_id=info_type.id WHERE publish_status=1 AND type_id=?';
        $result = $this->conn->fetch($sql,$typeid);
        
        return isset($result[0]['num']) ? $result[0]['num'] : 0;
    }
    public function fetchInfoById($id)
    {
        $sql = 'SELECT title,content,insert_time FROM info WHERE id=?';
        $result = $this->conn->fetch($sql,$id);
        
        return $result;
    }
    
    public function insertInfo($data)
    {
       return $this->conn->insert('info', $data);
    }
    public function updateInfo($data,$condition)
    {
        $this->conn->update('info', $data, $condition);
    }
}
?>
