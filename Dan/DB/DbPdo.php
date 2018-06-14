<?php
namespace Dan\Db;

use PDO;
use PDOException;
use Closure;

/**
 * 数据库驱动
 */
class DbPdo
{
    private $con;
    private $re_times = 5;
    private static $sql;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function __call($method, $arg)
    {
        return call_user_func_array([$this->con, $method], $arg);
    }

    public static function sql()
    {
        return self::$sql;
    }
    
    public function query($sql)
    {
        self::$sql = $sql;
        $query = $this->con->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exec($sql)
    {
        self::$sql = $sql;
        return $this->con->exec($sql);
    }

    public function begin()
    {
        if (!$this->con->inTransaction()) {
            $this->con->beginTransaction();
        }
    }

    public function checkConnect()
    {
        try {
            $this->con->query('SELECT 1;');
        } catch (PDOException $e) {
            $this->connect();
        }
    }

    public function create($tb,$data)
    {
        $masterOrderFields = $this->query("SHOW FULL COLUMNS FROM ".$tb);
        $masterOrderFields = array_column($masterOrderFields,'Field');
        $oldFields = array_keys($data);
        $newFields = array_intersect($masterOrderFields,$oldFields);
        $newData = [];
        foreach($newFields as $v){
            $newData[$v] = $data[$v];
        }
        return $newData;
    }

    public function add($tb,$data)
    {
        $arr = $this->create($tb,$data);
        $keys   = implode(',', array_keys($arr) );
        $fields = implode("','", array_values($arr) );
        $sql = "INSERT INTO `".$tb."` (".$keys.") VALUES ('".$fields."')";
        self::$sql = $sql;
        $status = $this->exec($sql);
        if($status){
            $id = $this->lastInsertId();
            return $id;
        }else{
            return false;
        }
    }

    public function find($sql)
    {
        $data = [];
        $res = $this->query($sql." limit 1");
        if (count($res) == 1) {
            $data = $res[0];
        }
        return $data;
    }

    /**
     * 更新操作
     * @param      $tb
     * @param      $array
     * @param null $where
     *
     * @return bool
     */
    function update($tb, $array, $where = null)
    {
        $sets = '';
        foreach($array as $key => $val) {
            $sets .= "`".$key."`='".$val."',";
        }
        $sets  = rtrim($sets, ','); //去掉SQL里的最后一个逗号
        $where = $where == null ? '' : ' WHERE '.$where;
        $sql   = "UPDATE ".$tb." SET ".$sets." ".$where;
        self::$sql = $sql;
        $res   = $this->exec($sql);
        if($res === false) {
            return false;
        } else {
            return $res;  //返回受影响的行数
        }
    }

    /**
     * 更新和新增操作
     * @param      $tb
     * @param      $data
     * @param null $where
     *
     * @return bool
     */
    public function createOrUpdate($tb, $data, $where = null, Closure $callback = null)
    {
        if(!empty($where)) {
            $data = $this->update($tb, $data, $where);
        } else {
            $data = $this->add($tb, $data);
        }

        $res = ($data === false ? false : ($callback === null ? $data : $callback()));
        return $res;
    }

    public function del($tb,$where)
    {
        $sql   = "DELETE FROM ".$tb." where ".$where;
        self::$sql = $sql;
        $res   = $this->exec($sql);
        if($res) {
            return $res;  //返回受影响的行数
        } else {
            return false;
        }
    }
}
