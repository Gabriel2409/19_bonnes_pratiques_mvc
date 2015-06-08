<?php

if (utilisateur_est_connecte()) 
{

	// On affiche la page d'erreur comme quoi l'utilisateur doit être connecté pour voir la page
	include CHEMIN_VUE_GLOBALE.'erreur_deja_connecte.php';
	
} 
else 
{


	// Ne pas oublier d'inclure la librairie Form
	include CHEMIN_LIB.'form.php';

	// "formulaire_connexion" est l'ID unique du formulaire
	$form_connexion = new Form('formulaire_connexion');

	$form_connexion->method('POST');

	$form_connexion->add('Text', 'nom_utilisateur')
	               ->label("Votre nom d'utilisateur");

	$form_connexion->add('Password', 'mot_de_passe')
	               ->label("Votre mot de passe");

	$form_connexion->add('Submit', 'submit')
	               ->value("Connectez-moi !");

	$form_connexion->add('Checkbox', 'connexion_auto')
					->required(false)
    			   ->label("Connexion automatique");
  

	// Pré-remplissage avec les valeurs précédemment entrées (s'il y en a)
	$form_connexion->bound($_POST);

	// Création d'un tableau des erreurs
	$erreurs_connexion = array();

	// Validation des champs suivant les règles
	if ($form_connexion->is_valid($_POST)) 
	{
		list($nom_utilisateur, $mot_de_passe) = $form_connexion->get_cleaned_data('nom_utilisateur', 'mot_de_passe');
		
		
		// combinaison_connexion_valide() est définit dans ~/modeles/membres.php
		include CHEMIN_FONCTION_CONTROLLER . 'crypt_pass.php';
		$id_utilisateur = combinaison_connexion_valide($nom_utilisateur, crypt_pass($mot_de_passe));
		
		// Si les identifiants sont valides
		if ($id_utilisateur) {

			$infos_utilisateur = lire_infos_utilisateur($id_utilisateur);
			
			// On enregistre les informations dans la session
			$_SESSION['id']     = $id_utilisateur;
			$_SESSION['pseudo'] = $nom_utilisateur;
			$_SESSION['avatar'] = $infos_utilisateur['avatar'];
			$_SESSION['email']  = $infos_utilisateur['adresse_email'];
			

			// Mise en place des cookies de connexion automatique
			if ($form_connexion->get_cleaned_data('connexion_auto'))
			{
				$navigateur = (!empty($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';

				$hash_cookie = salt_and_hash($nom_utilisateur,crypt_pass($mot_de_passe),$navigateur);
			
				setcookie( 'id',            $_SESSION['id'], time() + 365*24*3600, null, null, false, true);
				setcookie('connexion_auto', $hash_cookie,    time() + 365*24*3600, null, null, false, true);
			}


			// Affichage de la confirmation de la connexion
			include CHEMIN_VUE.'connexion_ok.php';
		
		}
		else 
		{

			$erreurs_connexion[] = "Couple nom d'utilisateur / mot de passe inexistant.";
			
			// On réaffiche le formulaire de connexion
			include CHEMIN_VUE.'formulaire_connexion.php';
		}
		
	}
	else 
	{
	    // On réaffiche le formulaire de connexion
	    include CHEMIN_VUE.'formulaire_connexion.php';
	}
}