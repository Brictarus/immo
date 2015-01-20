<?php

include_once 'generic-dao.php';

class AnnonceDao extends GenericDao {
  function __construct() {
    parent::__construct("annonce");
  }
}

/*require '../jsonwrapper/jsonwrapper.php';

$t = new stdClass();
$t->tp = 1;
$dao = new GenericDao("annonce");
$dao->connect();
try {
  echo 'test findOne :<br/>';
  $res = $dao->findOne(1, array("id", "label"));
  echo json_encode($res);
  
  echo '<br/><br/>test findAll :<br/>';
  $res = $dao->findAll(array("id", "label"));
  echo json_encode($res);
  
} catch (Exception $e) {
  throw $e;
} finally {
  $dao->disconnect();
}*/

?>