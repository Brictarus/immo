<?php

include_once 'generic-dao.php';

class PhotoDao extends GenericDao {
  function __construct() {
    parent::__construct("photo_annonce");
  }
  
  function create($extension) {
    $sql = "insert into ". $this->tableName ." (extension) ".
      "values ('".$extension."')";
    $insert_result = $this->daoConnector->query($sql);
    $id = mysql_insert_id();
    return $id;
  }
}

?>