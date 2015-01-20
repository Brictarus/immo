<?php
require '../jsonwrapper/jsonwrapper.php';
include_once '../dao/annonce-dao.php';
include_once 'generic-controller.php';

class AnnonceController extends GenericController {
  private $annonceDao = null;
  
  function __construct() {
    $this->annonceDao = new AnnonceDao();
  }
  
  function handleRequest() {
    header('Access-Control-Allow-Origin: *');
    switch($_SERVER['REQUEST_METHOD']) {
      case 'POST':
        $this->create();
        break;

      case 'GET':
        if (isset($_GET['id'])) {
          $this->findOne($_GET['id']);
        }
        else {
          $this->findAll();
        }
        break;

      case 'PUT':
        $this->update($_GET['id']);
        break;

      case 'DELETE':
        $this->delete($_GET['id']);
        break;

      default:
        header('HTTP/1.1 405 Method Not Allowed');
        break;
    }
  }
  
  function findAll() {
    $this->annonceDao->connect();
    $res = $this->annonceDao->findAll(null);
    $this->annonceDao->disconnect();
    $this->sendReponse($res);
  }

  function findOne($id) {
    $this->annonceDao->connect();
    $res = $this->annonceDao->findOne($id, null);
    $this->annonceDao->disconnect();
    if($res != null) {
      $this->sendReponse($res);
    } else {
      header('HTTP/1.1 404 Not Found');
    }
  }
}



error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
$controller = new AnnonceController();
$controller->handleRequest();
error_reporting(E_ALL);

?>