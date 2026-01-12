<?php
// Clé secrète pour le chiffrement des messages du chat
define('CHAT_SECRET_KEY', '....');

// -- les constantes genérales du projet, et connexion à la base de donnée en local -- //
// LES NOM MAIL NUMERO ET AUTRE CONSTANTE A UTILISER DANS LE SITE
const NOM_SITE = "E-Consulat Congo";
const NOM_PAYS = "Congo";
const NOM_SUJET = "l’Ambassade du Congo au Sénégal.";
const EMAIL_SUPPORT = "contact@exemple.com"; 
const TELEPHONE_SUPPORT = "+221770000000"; 
const ADRESSE_POSTALE = "123 Avenue de l'Environnement, Dakar, Sénégal"; 
const NOM_ENTREPRISE = "e";

//Personnalition du nom  du site
const DETUT = "E-";
const SECOND = "CONSULAT";
const LAST = "CONGO";
///////////////////////

define("URL_DRAPEAU","http://localhost/embassade/public/images/drapeau.jpg");
define("URL_EMBLEM","http://localhost/embassade/public/images/emblem.png");
// Préfixe du projet pour les chemins relatifs
define('URL_PREFIX', '/embassade');
define('NOM_APPLICATION', ''); // Nom du site aussi
define('DUREE_SPLASH', 4); // Durée en secondes pour l'écran de chargement
define('URL_BASE', 'http://localhost/embassade/'); // URL de base de l'application


// Infos de connexion à la base de données
define('NOM_BDD', '');         // Nom de ta base de données
define('NOM_UTILISATEUR', 'root');       // Nom d'utilisateur MySQL
define('MOT_DE_PASSE', '');           // Mot de passe MySQL (souvent vide en local)
define('HOTE_BDD', 'localhost');         // Hôte (localhost par défaut)

// Constantes de sécurité ou session
const SESSION_TIMEOUT = 3600; // Durée de session en secondes
