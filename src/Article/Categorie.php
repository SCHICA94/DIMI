<?php

namespace Article;

use Phunder\Core\Messager;
use App\App;

class Categorie

{
  /**
   * Ajouter un article
   *
   * @param string $titre     le titre de l'article
   * @param string $contenu   le contenu de l'article
   *
   * @return bool true si inséré en BDD
   */
  public static function ajouter(string $titre) : bool
  {
      // Validation de données
      if (!self::validation($titre, $contenu)) {
          return false;
      }

      // Insertion en base de données
      $insert = App::$db->prepare(
          'INSERT INTO categorie (
              titre,
              date_publication,
              contenu
          ) VALUES (
              :titre,
              NOW(),
              :contenu
          )'
      );
      // execution
      $resultat = $insert->execute([
          'titre'   => $titre,
          'contenu' => $contenu
      ]);

      if (!$resultat) {
          Messager::message(Messager::MSG_WARNING, 'L\'article n\'a pas pu être enregistré');
          return false;
      }

      Messager::message(Messager::MSG_SUCCESS, 'Article ajouté');
      return true;
  }
}
