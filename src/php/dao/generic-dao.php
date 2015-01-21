<?php

include_once 'mysql.php';

class GenericDao {
  
  protected $daoConnector = null;
  
  function __construct($tableName) {
    if ($tableName == null) {
      throw new Exception('tableName ne doit pas être null');  
    }
    $this->daoConnector = new MySql();
    $this->tableName = $tableName;
  }
  
  protected function extractColumnForSelect($fields) {
    if ($fields == null) {
      return '*';
    }
    else {
      if (gettype($fields) != "array") {
        throw new Exception('fields doit être un tableau'); 
      }
      if (sizeof($fields) == 0) {
        throw new Exception('fields ne doit pas être null'); 
      }
      $columnsSelected = array();
      foreach($fields as $value){
        array_push($columnsSelected, $value);
      }
      return join(", ", $columnsSelected);
    }
  }
  
  function connect($db = null) {
    return $this->daoConnector->connect();
  }
  
  function disconnect($db = null) {
    return $this->daoConnector->disconnect();
  }
  
  function findOne($id, $fields, $idField = "id") {
    // construction de la requête
    $sql = 'SELECT '.$this->extractColumnForSelect($fields)
      .' FROM ' . $this->tableName 
      .' WHERE ' . $idField . '=' . $id;
    
    // Execution de la requête
    $retval = $this->daoConnector->query($sql, $this->daoConnector->sqlConnection);
    if(!$retval) {
      throw new Exception('Impossible de récupérer les données lors de l\'éxécution de la requête : ' . mysql_error());
    }
    //echo $sql . '<br>';
    // récupération des resultats
    if($row = $this->daoConnector->fetch_array($retval)) {
      return $row;
    } else {
      return null;  
    }
  }
  
  function findAll($fields = null) {
    // construction de la requête
    $sql = 'SELECT '.$this->extractColumnForSelect($fields)
      .' FROM ' . $this->tableName;
    
    // Execution de la requête
    $retval = $this->daoConnector->query($sql, $this->daoConnector->sqlConnection);
    if(!$retval) {
      throw new Exception('Impossible de récupérer les données lors de l\'éxécution de la requête : ' . mysql_error());
    }
    //echo $sql . '<br>';
    // récupération des resultats
    $tempArr = array();
    while($row = $this->daoConnector->fetch_array($retval)) {
      array_push($tempArr, $row);
    }
    return $tempArr;
  }
  
  function create($data) {
    return null;
  }
  
  function update($id, $fields = null, $idField = "id") {
    
  }
  
  function delete($id, $idField = "id") {
    
  }
  
  function deleteAll() {
    // construction de la requête
    $sql = 'DELETE FROM '.$this->tableName;
    
    // Execution de la requête
    $retval = $this->daoConnector->query($sql, $this->daoConnector->sqlConnection);
    if(!$retval) {
      throw new Exception('Impossible de récupérer les données lors de l\'éxécution de la requête : ' . mysql_error());
    }
  }
}

?>