<?php


class GenericController {
  function xml_encode($mixed, $domElement = NULL, $DOMDocument = NULL) {
    if (is_null($DOMDocument)) {
      $DOMDocument = new DOMDocument;
      $DOMDocument->formatOutput = true;
         
      $rootNode = $DOMDocument->createElement('entries');
      $DOMDocument->appendChild($rootNode);
              
      $this->xml_encode($mixed, $rootNode, $DOMDocument);
          
      echo @$DOMDocument->saveXML();
    } else {
      if (is_array($mixed)) {
        foreach ($mixed as $index=>$mixedElement) {
          if (is_int($index)) {
              $nodeName = 'entry';
          } else {
              $nodeName = $index;
          }
          $node = $DOMDocument->createElement($nodeName);
          $domElement->appendChild($node);
          $this->xml_encode($mixedElement, $node, $DOMDocument);
        }
      } else {
          // TODO: test if CDATA if needed
          $new_node = $DOMDocument->createTextNode($mixed);

          $domElement->appendChild($new_node);
      }
    }
  }
  
  function sendDataAsJson($data) {
    header('Content-Type:application/json; charset=utf-8');
    echo json_encode($data);
  }
  
  function sendDataAsXml($data) {
    header('Content-Type:text/xml; charset=utf-8');
    echo $this->xml_encode($data);
  }
  
  function sendReponse($data) {
    $acceptHeader = $_SERVER['HTTP_ACCEPT'];
    $func = 'sendDataAsJson';
    if ($acceptHeader != null) {
      // ne fonctionne pas car ca peut etre une liste....
      /*switch ($acceptHeader) {
        case "text/xml":
          $func = 'sendDataAsXml';
          break;
        case "application/json":
        case "*\/*":
        case "*":
          $func = 'sendDataAsJson';
          break;
        default:
          $func = 'sendUnsupportedMediaTypeHeader';
          break;
      }*/
      if (strrpos($acceptHeader, "application/json") !== false) {
        $func = 'sendDataAsJson';
      } else if (strrpos($acceptHeader, "text/xml") !== false) {
        $func = 'sendDataAsXml';
      } else {
        $func = 'sendUnsupportedMediaTypeHeader';
      }
    }
    $this->$func($data);
  }
  
  function sendUnsupportedMediaTypeHeader() {
    header('HTTP/1.1 415 Unsupported Media Type');
  }
}
?>