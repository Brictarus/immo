<?php

include_once '../dao/annonce-dao.php';
include_once '../dao/photo-dao.php';
include_once 'generic-controller.php';

class AnnonceController extends GenericController
{
  private $annonceDao = null;

  function __construct()
  {
    $this->annonceDao = new AnnonceDao();
    $this->photoDao = new PhotoDao();
  }

  function handleRequest()
  {
    header('Access-Control-Allow-Origin: *');
    $body = null;
    switch ($_SERVER['REQUEST_METHOD']) {
      case 'POST':
        //$this->create();
        $body = file_get_contents('php://input');
        $this->create(json_decode($body));
        break;

      case 'GET':
        if (isset($_GET['id'])) {
          $this->findOne($_GET['id']);
        } else {
          $this->findAll();
        }
        break;

      case 'PUT':
        $body = file_get_contents('php://input');
        $this->update(json_decode($body));
        break;

      case 'DELETE':
        $this->delete($_GET['id']);
        break;

      default:
        header('HTTP/1.1 405 Method Not Allowed');
        break;
    }
  }

  function findAll()
  {
    $this->annonceDao->connect();
    $res = $this->annonceDao->findAll(null);
    $this->annonceDao->disconnect();
    $this->sendReponse($res);
  }

  function findOne($id)
  {
    settype($id, "integer");
    $conn = $this->annonceDao->connect();
    $this->photoDao->connect($conn);
    $res = $this->_findOne($id);
    $this->annonceDao->disconnect();
    if ($res != null) {
      $this->sendReponse($res);
    } else {
      header('HTTP/1.1 404 Not Found');
    }
  }

  private function _findOne($id, $fetchPhotos = true)
  {
    settype($id, "integer");
    $entity = $this->annonceDao->findOne($id, null);
    if ($entity == null) {
      return null;
    } else {
      if ($fetchPhotos) {
        $byAnnonceId = $this->photoDao->findByAnnonceId($id);
        $entity["photos"] = $byAnnonceId;
      }
    }
    return $entity;
  }

  function create($annonce)
  {
    $conn = $this->annonceDao->connect();
    $this->photoDao->connect($conn);
    $res = $this->annonceDao->create($annonce);
    if (sizeof($annonce->photos) > 0) {
      $photoIds = array();
      foreach ($annonce->photos as $photo) {
        $photoId = $photo->id;
        settype($photoId, "integer");
        array_push($photoIds, $photoId);
      }
      $this->photoDao->updateAnnonceId($photoIds, $res);
    }
    $new = $this->_findOne($res);
    $this->annonceDao->disconnect();
    $this->sendReponse($new);
  }

  function update($annonce)
  {
    $conn = $this->annonceDao->connect();
    $this->photoDao->connect($conn);
    $annonceId = $annonce->id;
    settype($annonceId, "integer");
    $this->annonceDao->update($annonceId, $annonce);
    if (sizeof($annonce->photos) > 0) {
      $photoIds = array();
      foreach ($annonce->photos as $photo) {
        array_push($photoIds, $photo->id);
      }
      $this->photoDao->updateAnnonceId($photoIds, $annonceId);
    }
    $res = $this->_findOne($annonceId);
    $this->annonceDao->disconnect();
    $this->sendReponse($res);
  }

  function delete($id)
  {
    settype($id, "integer");
    if ($id != null) {
      $conn = $this->annonceDao->connect();
      $this->photoDao->connect($conn);
      $this->photoDao->deleteByAnnonceId($id);
      $this->annonceDao->delete($id);
      $this->annonceDao->disconnect();
    }
    header("HTTP/1.1 204 No Content");
  }
}


error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
$controller = new AnnonceController();
$controller->handleRequest();
error_reporting(E_ALL);

?>