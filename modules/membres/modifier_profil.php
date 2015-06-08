<?php

if (empty($_GET['id']) or !is_numeric($_GET['id'])) 
{

	include CHEMIN_VUE.'erreur_parametre_profil.php';

}
else
{

	if (!utilisateur_est_connecte()) 
	{
		// On affiche la page d'erreur comme quoi l'utilisateur doit être connecté pour voir la page
		include CHEMIN_VUE_GLOBALE.'erreur_non_connecte.php';
		
	}

	elseif (htmlspecialchars($_GET['id']) != $_SESSION['id']) 
	{
		include CHEMIN_VUE.'erreur_mauvais_profil.php';

	}




	 else 
	{
		$infos_utilisateur = lire_infos_utilisateur($_SESSION['id']);
				
		// On enregistre les informations dans la session
		
		
		
		// Ne pas oublier d'inclure la librairie Form
		include CHEMIN_LIB.'form.php';
		
		
		$form_modif_email = new Form("form_modif_email");
		$form_modif_email->method('POST');
		
		$form_modif_email->add('Email', 'adresse_email')
	                         ->label("Votre adresse email")
	                         ->Required(false)
	                         ->value($_SESSION['email']);

	   $form_modif_email->add('Submit', 'submit')
	                         ->value("Mettre à jour adresse mail !");

	    $form_modif_avatar = new Form("form_modif_avatar");
		$form_modif_avatar->method('POST');               
		
		$form_modif_avatar->add('Checkbox', 'suppr_avatar')
	                         ->label("Je veux supprimer mon avatar")
	                         ->Required(false);
		
		$form_modif_avatar->add('File', 'avatar')
	                         ->label("Votre avatar (facultatif)")
	                         ->Required(false);
		
		$form_modif_avatar->add('Submit', 'submit')
	                         ->value("Mettre à jour l'avatar !");
		
		// "form_modif_mdp" est l'ID unique du formulaire
		$form_modif_mdp = new Form("form_modif_mdp");
		$form_modif_mdp->method('POST');
		
		$form_modif_mdp->add('Password', 'mdp_ancien')
	                       ->label("Votre ancien mot de passe");
		
		$form_modif_mdp->add('Password', 'mdp')
	                       ->label("Votre nouveau mot de passe");
		
		$form_modif_mdp->add('Password', 'mdp_verif')
	                       ->label("Votre nouveau mot de passe (vérification)");
		
		$form_modif_mdp->add('Submit', 'submit')
	                       ->value("Modifier mon mot de passe !");
		
		// Création des tableaux des erreurs (un par formulaire)
		$erreurs_form_modif_email = array();
		$erreurs_form_modif_avatar = array();
		$erreurs_form_modif_mdp   = array();
		
		// et d'un tableau des messages de confirmation
		$email_confirm = array();
		$avatar_confirm = array();
		$mdp_confirm = array();
		// Validation des champs suivant les règles en utilisant les données du tableau $_POST
		if ($form_modif_email->is_valid($_POST)) 
		{
		
			$adresse_email = $form_modif_email->get_cleaned_data('adresse_email');
		
		
			// Si l'utilisateur veut modifier son adresse e-mail
			if (!empty($adresse_email)) 
			{
		
				$test = maj_email_membre($_SESSION['id'], $adresse_email);
		
				if (true === $test) 
				{
		
					// Ça a marché, trop cool !
					$email_confirm[] = "Adresse e-mail mise à jour avec succès !";
					$_SESSION['email']  = $infos_utilisateur['adresse_email'];
		
				// Gestion des doublons
				}
				else
				{
		
					// Changement de nom de variable (plus lisible)
					$erreur =& $test;
					
						
					// On vérifie que l'erreur concerne bien un doublon
					if (23000 == $erreur[0]) { // Le code d'erreur 23000 signifie "doublon" dans le standard ANSI SQL
						preg_match('#Duplicate entry \'(.+)\' for key#', $erreur[2], $valeur_probleme);
						$valeur_probleme = $valeur_probleme[1];
		
						if ($adresse_email == $valeur_probleme) {
		
							$erreurs_form_modif_email[] = "Cette adresse e-mail est déjà utilisée.";
		
						} else 
						{
		
							$erreurs_form_modif_email[] = "Erreur ajout SQL : doublon non identifié présent dans la base de données.";
						}
		
					}
					else 
					{
		
						$erreurs_form_modif_email[] = sprintf("Erreur ajout SQL : cas non traité (SQLSTATE = %d).", $erreur[0]);
					}
		
				}
			}
		}
		elseif ($form_modif_avatar->is_valid($_POST))
		{ 
			$avatar = $form_modif_avatar->get_cleaned_data('avatar');
			$suppr_avatar = $form_modif_avatar->get_cleaned_data('suppr_avatar');
			// Si l'utilisateur veut supprimer son avatar...
			if (!empty($suppr_avatar)) 
			{
				maj_avatar_membre($_SESSION['id'], '');
				$_SESSION['avatar'] = '';
		
				$avatar_confirm[] = "Avatar supprimé avec succès !";
		
			// ... ou le modifier !
			}
			 elseif (!empty($avatar['tmp_name'])) 
			{
							
				$avatar_exension=strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));
				$extensions_autorisees=array('jpg','png','gif');
				
					if (!in_array($avatar_exension, $extensions_autorisees))
				{
						$erreurs_form_modif_avatar[]='Mauvaise extension avatar'; // on n'affiche rien.
				}
				elseif ($avatar['size']>MAX_SIZE_AVATAR)
				{
						$erreurs_form_modif_avatar[]='Taille avatar trop grande';
				}
				else
				{
					

					// On souhaite utiliser la librairie Image
					include CHEMIN_LIB.'image.php';
					// Redimensionnement et sauvegarde de l'avatar
					$avatar = new Image($avatar['tmp_name']);
					$avatar->resize_to(AVATAR_LARGEUR_MAXI, AVATAR_HAUTEUR_MAXI);

					$avatar_filename = DOSSIER_AVATAR . $_SESSION['id'] . '.' . $avatar_exension;
					$avatar->save_as($avatar_filename);


					// Mise à jour de l'avatar dans la table
					// maj_avatar_membre() est défini dans ~/modeles/membres.php
					maj_avatar_membre($_SESSION['id'] , $avatar_filename);
					$avatar_confirm[] = "Avatar modifié avec succès !";
					$_SESSION['avatar'] = $infos_utilisateur['avatar'];

				}
			} //endif (!empty($avatar)) 

		
		}
		else if ($form_modif_mdp->is_valid($_POST)) 
		{
		
			// On vérifie si les 2 mots de passe correspondent
			if ($form_modif_mdp->get_cleaned_data('mdp') != $form_modif_mdp->get_cleaned_data('mdp_verif')) 
			{
		
				$erreurs_form_modif_mdp[] = "Les deux mots de passes entrés sont différents !";
		
			// C'est bon, on peut modifier la valeur dans la BDD
			}
			else
			{
				include (CHEMIN_FONCTION_CONTROLLER . 'crypt_pass.php');
				$mdp=$form_modif_mdp->get_cleaned_data('mdp');
				$old_mdp=$form_modif_mdp->get_cleaned_data('mdp_ancien');
		
				$test =maj_mot_de_passe_membre($_SESSION['id'], crypt_pass($mdp), crypt_pass($old_mdp));
				echo '<pre>';
				print_r($test);
				echo '</pre>';
				if($test) 
				{
					$mdp_confirm[] = "Votre mot de passe a été modifié avec succès !";
				}
				else $erreurs_form_modif_mdp[]= 'Le mot de passe entré ne correspond pas à l\'ancien mot de passe';

			}
		
		}
	




	// Affichage des formulaires de modification du profil
	include (CHEMIN_VUE. 'formulaires_modifier_profil.php');
	
	}
}