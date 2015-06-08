<?php

// Inclusion du fichier de configuration (qui définit des constantes)
include 'global/config.php';


// Utilisation et démarrage des sessions
session_start();

// Inclusion de Pdo2, potentiellement utile partout
include CHEMIN_LIB.'pdo2.php';


// Vérifie si l'utilisateur est connecté   
function utilisateur_est_connecte() 
{
 
	return !empty($_SESSION['id']);
}

function salt_and_hash($nom,$mdp,$nav)
{

	$result=sha1('WzsUf49' . $nom . '7ZwhTf4*^Lk' . $mdp . ';wfZ54?' . $nav . '^bHy_6Yr27');
	return $result;
} 

// Vérifications pour la connexion automatique

// On a besoin du modèle des membres
include CHEMIN_MODELE.'membres.php';

// Le mec n'est pas connecté mais les cookies sont là, on y va !
if (!utilisateur_est_connecte() && !empty($_COOKIE['id']) && !empty($_COOKIE['connexion_auto']))
{
	$infos_utilisateur = lire_infos_utilisateur($_COOKIE['id']);
	
	if (false !== $infos_utilisateur)
	{
		$navigateur = (!empty($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$hash = salt_and_hash($infos_utilisateur['nom_utilisateur'],$infos_utilisateur['mot_de_passe'],$navigateur);
		
		if ($_COOKIE['connexion_auto'] == $hash)
		{
			// On enregistre les informations dans la session
			$_SESSION['id']     = $_COOKIE['id'];
			$_SESSION['pseudo'] = $infos_utilisateur['nom_utilisateur'];
			$_SESSION['avatar'] = $infos_utilisateur['avatar'];
			$_SESSION['email']  = $infos_utilisateur['adresse_email'];
		}
	}
}