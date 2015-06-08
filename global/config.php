<?php

// Identifiants pour la base de données. Nécessaires a PDO2.
define('SQL_DSN',      'mysql:dbname=mvc_membres;host=localhost');
define('SQL_USERNAME', 'root');
define('SQL_PASSWORD', '');


// Chemins à utiliser pour accéder aux vues/modeles/librairies
$module = empty($module) ? !empty($_GET['module']) ? $_GET['module'] : 'index' : $module;


// definir le bon CHEMIN_VUE
if (empty($module))
{
	if (!empty($_GET['module']))
	{
		$module = htmlspecialchars($_GET['module']);
	}
	else 
	{
		$module = 'index';
	}
}
	if (!is_dir('modules/'.$module.'/vues/'))
	{
		$module='index';
	}
	




define('CHEMIN_VUE',    'modules/'.$module.'/vues/');
define('CHEMIN_FONCTION_CONTROLLER',    'modules/'.$module.'/fonctions/');
define('CHEMIN_MODELE', 'modeles/');
define('CHEMIN_LIB',    'libs/');
define('CHEMIN_VUE_GLOBALE', 'vues_globales/');
// Configurations relatives à l'avatar

define('AVATAR_LARGEUR_MAXI', 100);
define('AVATAR_HAUTEUR_MAXI', 100);
define('MAX_SIZE_AVATAR', 50000);
define('DOSSIER_AVATAR', 'images/avatars/');