<?php

require_once '../process-image.php';
require_once '../dao/photo-dao.php';
require_once '../dao/annonce-dao.php';
require_once '../service/annonce-service.php';


class SeLogerAnnonceService {

  function __construct() {
    $this->dao = new PhotoDao();
    $this->annonceDao = new AnnonceDao();
    $this->annonceService = new AnnonceService();
  }

  function createFromUrl($srcUrl) {
    $html = file_get_contents($srcUrl);
    $dom = new DOMDocument();
    @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);

    $annonce = new stdClass();
    $annonce->label = $this->getLabel($dom);

    $finder = new DomXPath($dom);

    $annonce->id = null;
    $annonce->prix = $this->getPrice($dom);
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

    $photoUrls = $this->getPhotoUrls($finder);

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

    return "";
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
    $h1List = $dom->getElementsByTagName('h1');
    if ($h1List->length > 0) {
      $h1 = $h1List->item(0);
      $textContent = $h1->textContent;
      $textContent = str_replace("\n", ' ', $textContent);
      $textContent = preg_replace('/\s+/', ' ', $textContent);
      //$textContent = $this->suppr_accents($textContent);
      return $textContent;
    } else {
      return "titre indéfini";
    }
  }

  /**
   * @param $finder
   * @return int
   */
  protected function getPrice($dom) {
    $res = null;
    $queryResult = $dom->getElementById("price");
    if ($queryResult != null) {
      $rawText = $queryResult->textContent;
      if ($rawText != null && strlen($rawText) > 0) {
        $res = $this->parseInteger($rawText);
      }
    }
    return $res;
  }

  private function parseInteger($text) {
    $price = 0;
    $index = 0;
    $text = str_replace(' ', '', $text);
    $length = strlen($text);
    while ($index < $length) {
      $char = $text[$index];
      if (ctype_digit($char) === true) {
        $price *= 10;
        $price += intval($char);
      }
      $index++;
    }
    return $price;
  }

  /**
   * @param $finder
   * @return string
   */
  protected function getTypeLogement($finder)
  {
    return "T3";
  }

  /**
   * @param $finder
   * @return int
   */
  protected function getSurface($finder)
  {
    $queryResult = $finder->query("//ol[contains(concat(' ', normalize-space(@class), ' '), ' description-liste ')]/li[contains(text(), 'Surface')]");
    if ($queryResult != null && $queryResult->length > 0) {
      $surface = $this->parseInteger($queryResult->item(0)->textContent);
      return $surface;
    }
    return null;
  }

  /**
   * @param $finder
   * @return mixed
   */
  protected function getAdresse($finder)
  {
    $queryResult = $finder->query("//h2[contains(concat(' ', normalize-space(@class), ' '), ' detail-subtitle ')]/span");
    if ($queryResult != null && $queryResult->length > 0) {
      $textContent = trim($queryResult->item(0)->textContent);
      if ($this->startsWith($textContent, "à ") && strlen($textContent) > 2) {
        $textContent = substr($textContent, 2);
      }
      $result = ltrim($textContent);
      return $result;
    }
    return null;
  }

  private function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
  }

  /**
   * @param $srcUrl
   * @param $finder
   * @return string
   */
  protected function getDescription($finder, $srcUrl)
  {
    $queryResult = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' description ')]");
    if ($queryResult != null && $queryResult->length > 0) {
      $textContent = preg_replace('/\s+/', ' ', $queryResult->item(0)->textContent);
      //$textContent = str_replace('²', '2', $textContent);
      //$textContent = $this->suppr_accents($textContent);
      if (strlen($textContent) == 0) {
        $textContent = "Erreur a la recuperation de la description de l'annonce";
      }
      $result = $textContent . "\r\n\r\n" . $srcUrl;
      return $result;
    }
    return $srcUrl;
  }

  /**
   * @param $dom
   * @return array of image urls
   */
  protected function getPhotoUrls($finder)
  {
    $res = array();
    $imageTags = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' carrousel_image_visu ')]"); // $dom->getElementById('thumbs_carousel');
    if ($imageTags != null) {
      foreach ($imageTags as $image) {
        $url = $image->getAttribute('src');
        array_push($res, $url);
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

?>