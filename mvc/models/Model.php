<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 16:17
 */

namespace mvc\models;
use mvc\libs\Request;


class Model
{
  private $host = 'localhost';
  private $user = 'root';
  private $pass = '';
  private $dbName = 'test';
  private $tableName = '';
  private $connect;
  public function __construct($tableName = '')
  {
    global $Configs;

    $Request = new Request();
    $this->host = $Request->getVariable($Configs, ['conf', 'db', 'mysql', 'host'], $this->host);
    $this->user = $Request->getVariable($Configs, ['conf', 'db', 'mysql', 'user'], $this->user);
    $this->pass = $Request->getVariable($Configs, ['conf', 'db', 'mysql', 'pass'], $this->pass);
    $this->dbName = $Request->getVariable($Configs, ['conf', 'db', 'mysql', 'dbName'], $this->dbName);

    $this->tableName = (string)$tableName;

    $mysqli = new \mysqli($this->host, $this->user, $this->pass, $this->dbName);

    // проверяем соединение
    if (mysqli_connect_errno())
    {
      printf("Ошибка соединения: %s\n", mysqli_connect_error());
      exit();
    }

    $mysqli->set_charset('utf8');

    $this->connect = $mysqli;
  }
  public function get($fields = [], $id = null){
    $f_array = '*';
    if(is_array($fields) && !empty($fields)){
      $f_array = '`'.implode($fields, '`, `').'`';
    }

    $query = "SELECT $f_array FROM `$this->tableName`";
    if($id !== null){
      $query .= "WHERE id = '$id'";
    }
    $resultQuery = $this->connect->query($query);

    $data = [];

    if($resultQuery){
      while ($row = $resultQuery->fetch_assoc())
      {
        $data[] = $row;
      }
    }
    return $data;
  }
  public function insert($data = []){
    if(!is_array($data) || empty($data)){return false;}

    $fields = [];
    $values = [];

    foreach($data as $field => $value){
      $fields[] = $field;
      $values[] = $value;
    }
    $impl_fields = '`'.implode($fields, '`, `').'`';
    $impl_values = "'".implode($values, "', '")."'";
    $query = "INSERT INTO $this->tableName ($impl_fields) VALUES ($impl_values)";
    $resultQuery = $this->connect->query($query);
    if($resultQuery){
      return true;
    }else{
      return false;
    }
  }
  public function update($data = [], $key_name = '', $key_value = ''){
    if(!is_array($data) || empty($data)){return false;}

    $SET = '';
    foreach($data as $key => $value){
      $SET .= '`'.$key.'` = '.$value;
    }

    $query = "UPDATE $this->tableName SET ".$SET." WHERE `$key_name` = '$key_value''";
    $resultQuery = $this->connect->query($query);
    if($resultQuery){
      return true;
    }else{
      return false;
    }
  }

}