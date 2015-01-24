<?php

include_once 'config-bdd.php';

class MySql
{
  function __construct() {
    $conf = new ConfigBdd();
    $this->sqlServer = $conf->sqlServer;
    $this->sqlUser = $conf->sqlUser;
    $this->sqlPass = $conf->sqlPass;
    $this->dbName = $conf->dbName;
    
    $this->sqlConnection = null;
    $this->db = null;
  }
  
  function connect($conn=null) {
    if ($conn != null) {
      $this->sqlConnection = $conn;
      return $this->sqlConnection;
    }

    $this->sqlConnection = @mysql_connect($this->sqlServer,$this->sqlUser,$this->sqlPass);
    if (!$this->sqlConnection) {
      throw new Exception('Connexion impossible à la base de données.');
    }
    
    if (!@mysql_select_db($this->dbName)) {
      throw new Exception('Sélection de la base de données impossible');
    }
    return $this->sqlConnection;
  }
  
  function query($request) {
    if ($this->sqlConnection == null) {
      throw new Exception('sqlConnection est null');
    }
    $retval = @mysql_query($request, $this->sqlConnection);
    if(!$retval) {
      throw new Exception('Impossible d\'effectuer la requête : '.$request);
    } else {
      return $retval;
    }
  }
  
  function fetch_array($i) {
    return @mysql_fetch_array($i, MYSQL_ASSOC);
  }
  
  function nb_rows($i) {
    return @mysql_num_rows($this->requete[$i]);
  }
  
  function disconnect($conn = null) {
    $isDisconnected = @mysql_close($this->sqlConnection);
    if ($isDisconnected) {
      $this->sqlConnection = null;
    } else {
      throw new Exception('Impossible de fermer la connexion à la base de données');  
    }
  }
}

?>