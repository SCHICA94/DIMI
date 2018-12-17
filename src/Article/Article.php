<?php

namespace Article;

use Phunder\Core\Messager;
use App\App;

class Article
{
    private $id;
    private $titre;
    private $date_publication;
    private $contenu;

    public function __construct(int $id)
    {
        $article = App::$db->prepare('SELECT * FROM article WHERE id = :id');
        $article->bindParam(':id', $id, \PDO::PARAM_INT);
        $article->execute();

        // Si aucun article trouvé, lancer une exception
        if ($article->rowCount() != 1) {
            throw new \Exception('Article inconnu');
        }

        // Définir les propriétés
        $article                = $article->fetch(\PDO::FETCH_ASSOC);
        $this->id               = $article['id'];
        $this->titre            = $article['titre'];
        $this->date_publication = $article['date_publication'];
        $this->contenu          = $article['contenu'];
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getTitre() : string
    {
        return $this->titre;
    }

    public function getDatePublication() : string
    {
        return $this->date_publication;
    }

    public function getContenu() : string
    {
        return $this->contenu;
    }

    public function dateFr() : string
    {
        return (new \DateTime($this->date_publication))->format('d/m/Y');
    }

    public function markdownToHtml() : string
    {
        $parsedown = new \Parsedown();
        return $parsedown->text($this->contenu);
    }

    public function supprimer()
    {
        $supp = App::$db->prepare('DELETE FROM article WHERE id = :id');
        $supp->bindParam(':id', $this->id, \PDO::PARAM_INT);

        $resultat = $supp->execute();

        if (!$resultat) {
            Messager::message(Messager::MSG_WARNING, 'Impossible de supprimer l\'article');
        }
        Messager::message(Messager::MSG_SUCCESS, 'Article supprimé');
    }

    /**
     * Validation des données (ajout / modification)
     *
     * @param string $titre     titre de l'article
     * @param string $contenu   contenu de l'article
     *
     * @return bool true si les données sont valides
     */
    private static function validation(string $titre, string $contenu) : bool
    {
        $erreurs = [
            'titre vide'   => empty(trim($titre)),
            'contenu vide' => empty(trim($contenu))
        ];
        if (in_array(true, $erreurs)) {
            Messager::message(Messager::MSG_WARNING, array_search(true, $erreurs));
            return false;
        }

        return true;
    }

    /**
     * Ajouter un article
     *
     * @param string $titre     le titre de l'article
     * @param string $contenu   le contenu de l'article
     *
     * @return bool true si inséré en BDD
     */
    public static function ajouter(string $titre, string $contenu) : bool
    {
        // Validation de données
        if (!self::validation($titre, $contenu)) {
            return false;
        }

        // Insertion en base de données
        $insert = App::$db->prepare(
            'INSERT INTO article (
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


    /**
     * Obtenir une liste d'articles
     */
    public static function liste() : array
    {
        $liste = App::$db->query(
            'SELECT
                id
                , titre
                , date_publication
                , contenu
                , DATE_FORMAT(date_publication, "%d/%m/%Y") AS date_fr
                , SUBSTRING(contenu, 1, 100) AS extrait
            FROM article
            ORDER BY
                date_publication DESC,
                id DESC'
        );
        return $liste->fetchAll(\PDO::FETCH_ASSOC);
    }
}
