<?php

// Fichier de configuration obligatoire
require_once '../config/bootstrap.php';

// Alias d'espace de nom
// Pour utiliser la classe qui nous sert de routeur
use Phunder\PPRR\PPRR as Router;

// Alias des controlleurs
// Ils se trouvent dans: src/ + l'espace de nom
use Controller\Home as PageFront;
use Controller\Admin as PageBack;

// Paramétrage du routeur
// Pour utiliser des routes simples au lieu d'expressions rationnelles (RegEx)
Router::setDefaultMode(Router::MODE_STRING);

// Routes
// Alias::class     retourne le véritable nom complet d'une classe
new Router(
    [
        'R>(?:/|/home)?'   => [PageFront::class, 'index'],        // "R>" pour utiliser une RegEx
        '/login'           => [PageFront::class, 'connexion'],
        '/logout'          => [PageFront::class, 'deconnexion'],
        'R>/Categorie-(\d+)' => [PageFront::class, 'lireCategorie'],
        '/admin'           => [
<<<<<<< HEAD
            '/dashboard'    => [PageBack::class, 'listeArticles'],
            '/add_article'  => [PageBack::class, 'ajouterArticle'],
            'R>/edit-(\d+)' => [PageBack::class, 'modifierArticle'],
            'R>/supp-(\d+)' => [PageBack::class, 'supprimerArticle'],
            '/listeCategorie' => [PageBack::class, 'listeCategorie'],
=======
            '/dashboard'    => [PageBack::class, 'listeCategorie'],
            '/add_Categorie'  => [PageBack::class, 'ajouterCategorie'],
            'R>/edit-(\d+)' => [PageBack::class, 'modifierCategorie'],
            'R>/supp-(\d+)' => [PageBack::class, 'supprimerCategorie'],
>>>>>>> dc23978e303b7bdf75ecb9f2320e9cc5d64a3f63
            '/add_categorie'  => [PageBack::class, 'ajouterCategorie'],

        ]
    ],
    [PageFront::class, 'page_404']
);
