<?php

include_once 'generic-dao.php';

class AnnonceDao extends GenericDao
{
  function __construct()
  {
    parent::__construct("annonce");
    $this->entityFields = array(
      "adresse" => "string",
      "ascenceur" => "boolean",
      "cave" => "boolean",
      "prix" => "integer",
      "ch_chauffage" => "boolean",
      "ch_eau_chaude" => "boolean",
      "ch_eau_froide" => "boolean",
      "ch_entretien_commun" => "boolean",
      "ch_gardien" => "boolean",
      "cuisine_ouverte" => "boolean",
      "date_creation" => "date",
      "description" => "string",
      "etage" => "integer",
      "label" => "string",
      "montant_charges" => "integer",
      "nb_chambres" => "integer",
      "nb_etages" => "integer",
      "stationnement" => "boolean",
      "surface" => "integer",
      "taxe_fonciere" => "integer",
      "taxe_habitation" => "integer",
      "type_logement" => "string",
      "type_stationnement" => "string"
    );
  }

  private function getMappings($data)
  {
    return array(
      "adresse" => $data->adresse,
      "ascenceur" => $data->ascenceur,
      "cave" => $data->cave,
      "prix" => $data->prix,
      "ch_chauffage" => $data->ch_chauffage,
      "ch_eau_chaude" => $data->ch_eau_chaude,
      "ch_eau_froide" => $data->ch_eau_froide,
      "ch_entretien_commun" => $data->ch_entretien_commun,
      "ch_gardien" => $data->ch_gardien,
      "cuisine_ouverte" => $data->cuisine_ouverte,
      "date_creation" => $data->date_creation,
      "description" => $data->description,
      "etage" => $data->etage,
      "label" => $data->label,
      "montant_charges" => $data->montant_charges,
      "nb_chambres" => $data->nb_chambres,
      "nb_etages" => $data->nb_etages,
      "stationnement" => $data->stationnement,
      "surface" => $data->surface,
      "taxe_fonciere" => $data->taxe_fonciere,
      "taxe_habitation" => $data->taxe_habitation,
      "type_logement" => $data->type_logement,
      "type_stationnement" => $data->type_stationnement
    );
  }

  function findOne($id, $fields, $idField = "id")
  {
    $res = parent::findOne($id, $fields, $idField);
    if ($res != null) {
      foreach ($res as $key => $value) {
        $type = $this->entityFields[$key];
        if ($value != null && $type != null) {
          switch ($type) {
            case "integer":
            case "boolean":
              settype($value, $type);
              $res[$key] = $value;
              break;
            default:
              break;
          }
        }
      }
    }
    return $res;
  }

  function create($data)
  {
    $data->date_creation = "now()";
    $mappings = $this->getMappings($data);
    $fixedMappings = $this->checkFields($mappings);
    $keys = array();
    $values = array();
    foreach ($fixedMappings as $key => $value) {
      $fieldType = $this->entityFields[$key];
      if ($fieldType != null) {
        if ($value != null) {
          array_push($keys, $key);
          $val = $value;
          if ($fieldType === "string") {
            $val = "'" . $value . "'";
          }
          //echo "$key = $value <br>";
          array_push($values, $val);
        }
      }
    }

    $sql = "insert into " . $this->tableName . " (" . join(", ", $keys) . ") " .
      "values (" . join(", ", $values) . ")";
    //echo $sql."<br>";
    $this->daoConnector->query($sql);
    $id = mysql_insert_id();
    return $id;
  }

  function update($id, $data, $idField = "id")
  {
    $mappings = $this->getMappings($data);
    $fixedMappings = $this->checkFields($mappings);
    $assignments = array();
    foreach ($fixedMappings as $key => $value) {
      if ($key != "date_creation") {

        $fieldType = $this->entityFields[$key];
        if ($fieldType != null) {
          $val = $value;
          if ($value == null) {
            $val = "null";
          } else {
            if ($fieldType === "string") {
              $val = "'" . $value . "'";
            }
          }
          array_push($assignments, $key . "=" . $val);
        }
      }
    }

    $sql = "UPDATE " . $this->tableName .
      " SET " . join(", ", $assignments) .
      " WHERE $idField = $id";
    //echo $sql . "<br>";
    $this->daoConnector->query($sql);
  }

  function findAll($fields = null)
  {
    // construction de la requête
    $sql = 'SELECT a.id, a.label, a.date_creation, a.adresse, a.prix, count(pa.id) as nb_photos '
      . ' FROM ' . $this->tableName . ' a '
      . ' INNER JOIN photo_annonce pa on pa.annonce_id = a.id '
      . ' GROUP BY pa.annonce_id';
    /*echo $sql;*/

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