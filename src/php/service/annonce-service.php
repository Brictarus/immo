<?php

class AnnonceService {
  /**
   * @param $annonce
   * @return array|null
   */
  function create($annonce, $annonceDao, $photoDao)
  {
    $res = $annonceDao->create($annonce);
    if (sizeof($annonce->photos) > 0) {
      $photoIds = array();
      foreach ($annonce->photos as $photo) {
        $photoId = $photo->id;
        settype($photoId, "integer");
        array_push($photoIds, $photoId);
      }
      $photoDao->updateAnnonceId($photoIds, $res);
    }
    $new = $this->findOne($res, $annonceDao, $photoDao);
    return $new;
  }

  function findOne($id, $annonceDao, $photoDao, $fetchPhotos = true)
  {
    settype($id, "integer");
    $entity = $annonceDao->findOne($id, null);
    if ($entity == null) {
      return null;
    } else {
      if ($fetchPhotos) {
        $byAnnonceId = $photoDao->findByAnnonceId($id);
        $entity["photos"] = $byAnnonceId;
      }
    }
    return $entity;
  }
}