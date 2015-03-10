<?php

require_once 'generic-controller.php';
require_once '../process-image.php';
require_once '../dao/photo-dao.php';

class UploadImageController extends GenericController {
  
  function __construct() {
    $this->dao = new PhotoDao();
  }
  
  function handleRequest() {
    //continue only if $_POST is set and it is a Ajax request
    switch ($_SERVER['REQUEST_METHOD']) {
      case "POST":
        if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
          $this->uploadImage($_FILES['image_file']);
        }
        break;
      case "DELETE":
        if (isset($_GET['id'])) {
          $id = $_GET['id'];
          settype($id, "integer");
          $this->delete($id);
        }
        break;
    }
  }

  private function delete($id) {
    $this->dao->connect();
    $this->dao->delete($id);
    $this->dao->disconnect();
    header("HTTP/1.1 204 No Content");
  }
  
  private function uploadImage($file) {
    // check $_FILES['ImageFile'] not empty
    if(!isset($file) || !is_uploaded_file($file['tmp_name'])){
      die('Image file is Missing!'); // output error when above checks fail.
    }

    //get uploaded file info before we proceed
    $image_name = $file['name']; //file name
    $image_size = $file['size']; //file size
    $image_temp = $file['tmp_name']; //file temp

    $this->dao->connect();
    $created = create_photo($this->dao, $image_temp, $image_name);
    $this->dao->disconnect();
    if ($created != null) {
      $this->sendDataAsJson($created);
    }
  }
}

$controller = new UploadImageController();
$controller->handleRequest();