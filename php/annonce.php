<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
switch($_SERVER['REQUEST_METHOD']){
    case 'POST':
        save();
    break;
 
    case 'GET':
      if (isset($_GET['id'])) {
        findOne($_GET['id']);
      }
      else {
        fetch();
      }
    break;
 
    case 'PUT':
        update();
    break;
 
    case 'DELETE':
        delete();
    break;
 
    default:
        echo "erreur dans la méthode requise par le seveur";
    break;
}

function fetch() {
    // Connexion à la base de données
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'immo';
    
    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    if(! $conn )
    {
      die('Could not connect: ' . mysql_error());
    }
    
    // La requête à effectuer
    $sql = 'SELECT * FROM annonce';
    mysql_select_db($dbname);
    
    // Execution de la requête
    $retval = mysql_query( $sql, $conn );
    if(! $retval )
    {
      die('Could not get data: ' . mysql_error());
    }
    
    // récupération des resultats
    $tempArr = array();
    while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
    {
        array_push($tempArr, $row);
    }
    
    // affichage du resultat en json
    echo json_encode($tempArr);
    
    mysql_close($conn);
}

function findOne($id) {
    // Connexion à la base de données
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'immo';
    
    if ($id == null) {
      header('HTTP/1.0 400 Bad Request');
      return;
    }
  
    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    if(! $conn )
    {
      die('Could not connect: ' . mysql_error());
    }
    
    // La requête à effectuer
    $sql = 'SELECT * FROM annonce WHERE id='.$id;
    mysql_select_db($dbname);
    
    // Execution de la requête
    $retval = mysql_query( $sql, $conn );
    if(! $retval )
    {
      die('Could not get data: ' . mysql_error());
    }
    
    // récupération des resultats
    if($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
        // affichage du resultat en json
      echo json_encode($row);
    } else {
      header('HTTP/1.0 404 Not Found');
    }
    
    mysql_close($conn);
}

error_reporting(E_ALL);
?>