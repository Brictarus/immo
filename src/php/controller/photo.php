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
     /*else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
      $this->dao->connect();
      $newId = $this->dao->deleteAll();
      $this->dao->disconnect();
    }*/
    /*$this->dao->connect();
    $newId = $this->dao->create("toto");
    $this->dao->disconnect();
    echo $newId;*/
  }

  private function delete($id) {
    $this->dao->connect();
    $this->dao->delete($id);
    $this->dao->disconnect();
    header("HTTP/1.1 204 No Content");
  }
  
  private function uploadImage($file) {
    ############ Configuration ##############
    $thumb_square_size      = 200; //Thumbnails will be cropped to 200x200 pixels
    $max_image_size         = 500; //Maximum image size (height and width)
    $thumb_prefix           = "thumb_"; //Normal thumb Prefix
    $main_folder     = '../../data/pics/'; //upload directory ends with / (slash)
    $thumb_folder     = '../../data/thumbnails/'; //upload directory ends with / (slash)
    $jpeg_quality           = 90; //jpeg quality
    ##########################################
    
    // check $_FILES['ImageFile'] not empty
    if(!isset($file) || !is_uploaded_file($file['tmp_name'])){
      die('Image file is Missing!'); // output error when above checks fail.
    }

    //get uploaded file info before we proceed
    $image_name = $file['name']; //file name
    $image_size = $file['size']; //file size
    $image_temp = $file['tmp_name']; //file temp

    $image_size_info    = getimagesize($image_temp); //gets image size info from valid image file

    if($image_size_info){
      $image_width        = $image_size_info[0]; //image width
      $image_height       = $image_size_info[1]; //image height
      $image_type         = $image_size_info['mime']; //image type
    } else {
      die("Make sure image file is valid!");
    }

    //switch statement below checks allowed image type 
    //as well as creates new image from given file 
    switch($image_type){
      case 'image/png':
        $image_res =  imagecreatefrompng($image_temp); break;
      case 'image/gif':
        $image_res =  imagecreatefromgif($image_temp); break;           
      case 'image/jpeg': case 'image/pjpeg':
        $image_res = imagecreatefromjpeg($image_temp); break;
      default:
        $image_res = false;
    }

    if($image_res){
      //Get file extension and name to construct new file name 
      $image_info = pathinfo($image_name);
      $image_extension = strtolower($image_info["extension"]); //image extension
      $image_name_only = strtolower($image_info["filename"]);//file name only, no extension

      $this->dao->connect();
      $data = array(
        "extension" => $image_extension,
        "nom" => substr($image_name, 0, 64)
      );
      $newId = $this->dao->create($data);
      $result = $this->dao->findOne($newId, null);

      $this->dao->disconnect();
      
      
      //create a random name for new image (Eg: fileName_293749.jpg) ;
      //$randRes = rand(0, 9999999999);
      //$new_file_name = $image_name_only. '_' .  $newId . '.' . $image_extension;
      $new_file_name = $newId . '.' . $image_extension;

      //folder path to save resized images and thumbnails
      $thumb_save_folder  = $thumb_folder . $new_file_name; 
      $image_save_folder  = $main_folder . $new_file_name;

      //call normal_resize_image() function to proportionally resize image
      if(normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality))
      {
        //call crop_image_square() function to create square thumbnails
        if(!crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality))
        {
            die('Error Creating thumbnail');
        }

        /* We have succesfully resized and created thumbnail image */
        /*$res = array(
          "id" => $newId,
          "thumbnailUrl" => 'data/thumbnails/' . $new_file_name,
          "imageUrl" => "data/pics/". $new_file_name
        );*/
        $this->sendDataAsJson($result);
      }

      imagedestroy($image_res); //freeup memory
    }

  }
}

$controller = new UploadImageController();
$controller->handleRequest();