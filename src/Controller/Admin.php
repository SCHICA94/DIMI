<?php

namespace Controller;

use Phunder\Core\Controller\Controller as ControlleurFramework;
use Phunder\Core\Messager;
use Phunder\Core\User\UserManager;
use User\User;
use Article\Article;
use Article\Categorie;

class Admin extends ControlleurFramework
{
    public static function mandatoryData() : void
    {
        self::set('messages', Messager::output());
    }

    // Vérifier les droits d'accès de l'utilisateur courant
    public static function verifAdmin() : void
    {
        $manager = new UserManager();
        if (!$manager->isLoggedIn() || $manager->get('type') !== 'admin') {
            header("Location: ../");
        }
    }

    // Accueil - liste des articles
    public static function listeArticles() : void
    {
        self::verifAdmin();
        self::set('titre_HTML', 'Liste des articles');
        self::set('liste', Article::liste());

        self::render('admin/liste_articles.html');
    }

    // Ajouter un article
    public static function ajouterArticle() : void
    {
        self::verifAdmin();

        // Formulaire: ajout d'article
        if (isset($_POST['ajouter']) && User::verifToken($_POST['token'] ?? '')) {
            $ajout = Article::ajouter($_POST['titre'], $_POST['contenu']);

            // Si ajouté, vider le formulaire
            if ($ajout) {
                unset($_POST);
            }
        }

        self::set('titre_HTML', 'Ajouter un article');

        // Pré-remplissage du formulaire
        self::set('titre', $_POST['titre'] ?? '');
        self::set('contenu', $_POST['contenu'] ?? '');

        $manager = new UserManager();
        self::set('token', $manager->get('token'));

        self::render('admin/ajouter_article.html');
    }

    public static function supprimerArticle(int $id_article)
    {
        self::verifAdmin();

        try {
            $article = new Article($id_article);

            $article->supprimer();

            header("Location: dashboard");

        } catch (\Exception $e) {
            Messager::message(Messager::MSG_WARNING, $e->getMessage());
            self::set('titre_HTML', 'Erreur');
            self::render('erreur.html');
        }

    }

    // Ajouter une Categorie
    public static function ajouterCategorie() : void
    {
        self::verifAdmin();

        // Formulaire: ajout d'article
        if (isset($_POST['ajouter']) && User::verifToken($_POST['token'] ?? '')) {
            $ajout = Categorie::ajouter($_POST['titre']);

            // Si ajouté, vider le formulaire
            if ($ajout) {
                unset($_POST);
            }
        }

        self::set('titre_HTML', 'Ajouter une Categorie');

        // Pré-remplissage du formulaire
        self::set('titre_categorie', $_POST['titre'] ?? '');

        $manager = new UserManager();
        self::set('token', $manager->get('token'));

        self::render('admin/ajouter_categorie.html');
    }

    public static function supprimerCategorie(int $id_categorie)
    {
        self::verifAdmin();

        try {
            $article = new Article($id_article);

            $article->supprimer();

            header("Location: dashboard");

        } catch (\Exception $e) {
            Messager::message(Messager::MSG_WARNING, $e->getMessage());
            self::set('titre_HTML', 'Erreur');
            self::render('erreur.html');
        }

    }

}
