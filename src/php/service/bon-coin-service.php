<?php

require_once '../process-image.php';
require_once '../dao/photo-dao.php';
require_once '../dao/annonce-dao.php';
require_once '../service/annonce-service.php';

function printLine($arg) {
  echo $arg . '<br>';
}

class LeBonCoinAnnonceService {

  function __construct() {
    $this->dao = new PhotoDao();
    $this->annonceDao = new AnnonceDao();
    $this->annonceService = new AnnonceService();
  }

  function createFromUrl($srcUrl) {
    $html = file_get_contents($srcUrl);
    $dom = new DOMDocument();
    //@$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
    @$dom->loadHTML($html);

    $annonce = new stdClass();
    $annonce->label = $this->getLabel($dom);

    $finder = new DomXPath($dom);

    $annonce->id = null;
    $annonce->prix = $this->getPrice($finder);
    $annonce->type_logement = $this->getTypeLogement($finder);
    $annonce->surface = $this->getSurface($finder);
    $annonce->adresse = $this->getAdresse($finder);
    $annonce->description = $this->getDescription($finder, $srcUrl);

    $annonce->ascenceur = null;
    $annonce->cave = null;
    $annonce->ch_chauffage = null;
    $annonce->ch_eau_chaude = null;
    $annonce->ch_eau_froide = null;
    $annonce->ch_entretien_commun = null;
    $annonce->ch_gardien = null;
    $annonce->cuisine_ouverte = null;
    $annonce->date_creation = null;
    $annonce->etage = null;
    $annonce->montant_charges = null;
    $annonce->nb_chambres = null;
    $annonce->nb_etages = null;
    $annonce->stationnement = null;
    $annonce->taxe_fonciere = null;
    $annonce->taxe_habitation = null;
    $annonce->type_stationnement = null;
    $annonce->photo_favorite_id = null;

    $photoUrls = $this->getPhotoUrls($dom);

    $conn = $this->annonceDao->connect();
    $this->dao->connect($conn);

    // download + upload des photos
    $photosArr = null;
    if ($photoUrls != null && sizeof($photoUrls) > 0) {
      $photosArr = $this->uploadPhotosFromUrl($photoUrls);
      $annonce->photo_favorite_id = $photosArr[0]->id;
    } else {
      $photosArr = array();
    }
    $annonce->photos = $photosArr;

    // création de l'annonce
    $result = $this->annonceService->create($annonce, $this->annonceDao, $this->dao);

    $this->annonceDao->disconnect();

    return $result;
  }

  private function uploadPhotosFromUrl($photoUrls)
  {
    $tempDir = '../../data/temp/';
    $res = array();
    foreach ($photoUrls as $url) {
      $tempPath = $tempDir . basename($url);
      file_put_contents($tempPath, file_get_contents($url));


      $created = create_photo($this->dao, $tempPath, basename($tempPath));
      $toObj = $this->convertPhotoToObject($created);
      //$this->dao->disconnect();
      array_push($res, $toObj);

      unlink($tempPath);
    }
    return $res;
  }

  /**
   * @param $dom
   * @return mixed
   */
  private function getLabel($dom)
  {
    $textContent = $dom->getElementById('ad_subject')->textContent;
    $textContent = str_replace('²', '2', $textContent);
    $textContent = $this->suppr_accents($textContent);
    return $textContent;
  }

  /**
   * @param $finder
   * @return int
   */
  protected function getPrice($finder) {
    $res = null;
    $queryResult = $finder->query("//span[contains(@class, 'price')]");
    if ($queryResult != null && $queryResult->length > 0) {
      $rawText = $queryResult->item(0)->textContent;
      if ($rawText != null && strlen($rawText) > 0) {
        $res = intval(str_replace(' ', '', $rawText));
      }
    }
    return $res;
  }

  /**
   * @param $finder
   * @return string
   */
  protected function getTypeLogement($finder)
  {
    $queryResult = $finder->query('//th[text()="Pièces : "]/../td');
    if ($queryResult != null && $queryResult->length > 0) {
      return utf8_encode('T' . $queryResult->item(0)->textContent);
    }
    return null;
  }

  /**
   * @param $finder
   * @return int
   */
  protected function getSurface($finder)
  {
    $queryResult = $finder->query('//th[text()="Surface : "]/../td');
    if ($queryResult != null && $queryResult->length > 0) {
      return intval($queryResult->item(0)->textContent);
    }
    return null;
  }

  /**
   * @param $finder
   * @return mixed
   */
  protected function getAdresse($finder)
  {
    $queryResult = $finder->query('//th[text()="Ville :"]/../td');
    if ($queryResult != null && $queryResult->length > 0) {
      return utf8_encode($queryResult->item(0)->textContent);
    }
    return null;
  }

  /**
   * @param $srcUrl
   * @param $finder
   * @return string
   */
  protected function getDescription($finder, $srcUrl)
  {
    $queryResult = $finder->query("//div[@class='content']");
    if ($queryResult != null && $queryResult->length > 0) {
      $textContent = preg_replace('/\s+/', ' ', $queryResult->item(0)->textContent);
      $textContent = str_replace('²', '2', $textContent);
      $textContent = $this->suppr_accents($textContent);
      return $textContent . "\r\n\r\n" . $srcUrl;
    }
    return $srcUrl;
  }

  /**
   * @param $dom
   * @return array of image urls
   */
  protected function getPhotoUrls($dom)
  {
    $res = array();
    $thumbsContainer = $dom->getElementById('thumbs_carousel');
    if ($thumbsContainer != null) {
      $images = $thumbsContainer->getElementsByTagName('span');
      foreach ($images as $image) {
        $style = $image->getAttribute('style');
        if ($style != null) {
          $prefixSize = strlen("background-image: url('");
          $url = substr($style, $prefixSize, strlen($style) - $prefixSize - 3);
          $mUrl = str_replace("thumbs", "images", $url);
          array_push($res, $mUrl);
        }
      }
    }
    return $res;
  }

  /**
   * Supprimer les accents
   *
   * @param string $str chaîne de caractères avec caractères accentués
   * @param string $encoding encodage du texte (exemple : utf-8, ISO-8859-1 ...)
   * @return mixed|string
   */
  function suppr_accents($str, $encoding='utf-8')
  {
    // transformer les caractères accentués en entités HTML
    $str = htmlentities($str, ENT_NOQUOTES, $encoding);

    // remplacer les entités HTML pour avoir juste le premier caractères non accentués
    // Exemple : "&ecute;" => "e", "&Ecute;" => "E", "Ã " => "a" ...
    $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);

    // Remplacer les ligatures tel que : Œ, Æ ...
    // Exemple "Å“" => "oe"
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
    // Supprimer tout le reste
    $str = preg_replace('#&[^;]+;#', '', $str);

    return $str;
  }

  protected function convertPhotoToObject($created) {
    $photo = new stdClass();
    $photo->id          = $created["id"];
    $photo->extension   = $created["extension"];
    $photo->nom         = $created["nom"];
    $photo->commentaire = $created["commentaire"];
    $photo->annonce_id  = $created["annonce_id"];

    return $photo;
  }
}