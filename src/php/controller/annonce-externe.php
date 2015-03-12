<?php
require_once 'generic-controller.php';
require_once "../service/bon-coin-service.php";
require_once "../service/se-loger-service.php";

class ExternalAnnonceController extends GenericController {
  function handleRequest()
  {
    $body = null;
    switch ($_SERVER['REQUEST_METHOD']) {
      case 'POST':
        if (isset($_GET['url'])) {
          $srcUrl = $_GET['url'];
          $service = null;
          $pos = strrpos($srcUrl, "leboncoin.fr");
          if ($pos !== false) {
            $service = new LeBonCoinAnnonceService();
          } else {
            $pos = strrpos($srcUrl, "seloger.com");
            if ($pos !== false) {
              $service = new SeLogerAnnonceService();
            } else {
              header('HTTP/1.1 400 Bad Request');
              return;
            }
          }
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