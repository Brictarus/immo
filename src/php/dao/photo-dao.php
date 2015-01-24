<?php

include_once 'generic-dao.php';

class PhotoDao extends GenericDao
{
  function __construct()
  {
    parent::__construct("photo_annonce");
  }

  function create($data)
  {
    $sql = "insert into " . $this->tableName . " (nom, extension) " .
      "values ('" . $data["nom"] . "', '" . $data["extension"] . "')";
    $insert_result = $this->daoConnector->query($sql);
    $id = mysql_insert_id();
    return $id;
  }

  function updateAnnonceId($ids, $annonceId)
  {
    if (sizeof($ids) > 0) {
      $sql = "update $this->tableName set annonce_id = $annonceId where ";
      if (sizeof($ids) == 1) {
        $sql .= "id = $ids[0]";
      } else {
        $sql .= "id in (" . join(", ", $ids) . ")";
      }
      // echo $sql;
      $this->daoConnector->query($sql);
    }
  }

  function findByAnnonceId($annonceId) {
    // construction de la requête
    $sql = 'SELECT * '
      . ' FROM ' . $this->tableName
      . ' WHERE annonce_id = ' . $annonceId;

    // Execution de la requête
    $retval = $this->daoConnector->query($sql, $this->daoConnector->sqlConnection);
    if (!$retval) {
      throw new Exception('Impossible de récupérer les données lors de l\'éxécution de la requête : ' . mysql_error());
    }
    //echo $sql . '<br>';
    // récupération des resultats
    $tempArr = array();
    while ($row = $this->daoConnector->fetch_array($retval)) {
      array_push($tempArr, $row);
    }
    return $tempArr;
  }
}

/*$photoDao = new PhotoDao();
$photoDao->updateAnnonceId(array(12, 1, 3), 2);
echo "<br>";
$photoDao->updateAnnonceId(array(3), 5);
echo "<br>";
$photoDao->updateAnnonceId(array(), 6);*/

?>