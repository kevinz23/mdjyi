<?php

class mysqlconnect{
    
    // Database Link object
    private $conn = null ;
    private $dsn;
    // Construct function 
   
    public function __construct($dbconfig) { 
        $host = $dbconfig['hostname'];
        $dbname = $dbconfig['dbname'];
        $this->user = $dbconfig['username'];
        $this->pwd = $dbconfig['password'];
        $this->dsn = "mysql:dbname=$dbname;host=$host";
        
    }
    
    public function connect() {
        try {  
            $params = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");
            $this->conn = new PDO($this->dsn, $this->user, $this->pwd,$params);
        } catch (PDOException $e) {  
            print_r($e->getMessage());  
        }  
    }
    
    public function execute($sqlstr,$param) {
        $sth = $this->conn->prepare($sqlstr);
        if (!is_array($param)) {
             $param = array($param);
        }
        foreach($param as $k=>$v){
            $type = PDO::PARAM_NULL;
            if (is_bool($v)) 
                $type =  PDO::PARAM_BOOL;
            elseif (is_int($v)) 
                $type =  PDO::PARAM_INT;
            elseif (is_string($v))
                $type= PDO::PARAM_STR;
            $sth->bindValue($k+1, $v,$type);
        }
        $sth->execute();
        if ($sth->errorCode() > 0) {
            print_r('Code:[' . $sth->errorCode() . '] ' . join(',', $sth->errorInfo()));
        }
        return $sth;
    }
    public function query($sql, $params = array())
    {
        try {
            $sth = $this->execute($sql, $params);
            if ($sth) {
                $rows = $sth->rowCount();
                return $rows > 0 ? $rows : true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception($sql);
            return false;
        }
    }
    public function fetch($sqlstr,$param = array()) {

        $sth = $this->execute($sqlstr,$param);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Insert 
    public function insert($table,array $params) {
       $cols = $vals = array();
        foreach ($params as $col => $val) {
                $cols[] = $col;
                $vals[] = '?';
        }
        $sql = 'INSERT INTO `' . $table . '`'
                . '(`' . implode('`, `', $cols) . '`)'
                . 'VALUES (' . implode(', ', $vals) . ');';
        $sth = $this->query($sql, array_values($params));
        if ($sth) {
                return $this->conn->lastInsertId();
        } else {
                return 0;
        }
    }
   
    // Update
    public function update($table, array $bind, array $condition){
        $sets = array();
        $vals = array();
        foreach ($bind as $col => $val) {
                $sets[] = "`$col`= ?";
                $vals[] = $val;
        }
        $where ='';
        foreach ($condition as $key => $val)
        {
                $where.="$key = ? and ";
                $vals[] = $val;
        }
        
        $where = substr($where, 0, strlen($where)-4);
        $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets)
                . (($where) ? ' WHERE ' . $where : '');
        return $this->query($sql, $vals);
    }
    
    // Delete
    public function delete($table, $where) {
        if (empty($where)) {
                return false;
        }
        $sql = 'DELETE FROM `' . $table .'`';
        $condition='';
        if (!empty($where) && !is_null($where) || $where!=array())
        {
                foreach ($where as $key=>$val)
                {
                        $condition.="$key = ? and ";
                        $data[] = $val;
                }
        }
        $where = substr($condition,0,strlen($condition)-4);
        $sql.= ' WHERE '.$where;
        return $this->query($sql,$data);
    }
    
    
    public function get($table,$field=array(),$where = array(),$limit=0,$offset=0,$join=null,$groupby=null,$orderby=null) {
        $data = array();
        $condition="";
        if (empty($field)||count($field)==0)
                $field='*';
        else if ($field =='num')
                $field = 'count(*) num';
        else{
                if (!is_array($field))
                    $field = array($field);
                $field = implode(",",$field);
        }
        $sql = "SELECT $field FROM $table ";
        if (!empty($join)&&count($join)!=0) {
                $sql.=" JOIN $join[0] ON $join[1] ";
        }  
        if (!empty($where) && !is_null($where) || $where!=array()){
            foreach ($where as $key=>$val) {
                    $condition.="$key=? and ";
                    $data[] = $val;
            }
            $where = substr($condition, 0, strlen($condition)-4);
            $sql.='WHERE '.$where;
        }
        
        if (!empty($groupby) && $groupby != null)
                $sql.=' group by '.$groupby;
        if (!empty($orderby) && $orderby != null)
                $sql.=' order by OrderID '.$orderby;
        if ($limit !=0 || $offset !=0)
                $sql.=' LIMIT '.$offset.','.$limit;
        return $this->fetch($sql,$data);;
    }
    public function begin()
    {
        return $this->conn->beginTransaction();
    }
    public function commit()
    {
        return $this->conn->commit();
    }
    public function rollback()
    {
        return $this->pdo->rollback();
    }
    public function close()
    {
        $this->conn = null;
    }
    
        
}

?>
