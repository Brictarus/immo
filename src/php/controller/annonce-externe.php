<?php
require_once 'generic-controller.php';
require_once "../service/bon-coin-service.php";

class ExternalAnnonceController extends GenericController {
  function handleRequest()
  {
    $body = null;
    switch ($_SERVER['REQUEST_METHOD']) {
      case 'POST':
        if (isset($_GET['url'])) {
          $srcUrl = $_GET['url'];
          $service = new LeBonCoinAnnonceService();
          $res = $service->createFromUrl($srcUrl);
          $this->sendReponse($res);
        } else {
          header('HTTP/1.1 400 Bad Request');
        }
        break;

      default:
        header('HTTP/1.1 405 Method Not Allowed');
        break;
    }
  }
}

$controller = new ExternalAnnonceController();
$controller->handleRequest();