<?php

function maj_avatar_membre($id_utilisateur , $avatar) {

	$pdo = PDO2::getInstance();

	$requete = $pdo->prepare("UPDATE membres SET
		avatar = :avatar
		WHERE
		id = :id_utilisateur");

	$requete->bindValue(':id_utilisateur', $id_utilisateur);
	$requete->bindValue(':avatar', $avatar);

	return $requete->execute();
}

function valider_compte_avec_hash($hash_validation) {

	$pdo = PDO2::getInstance();

	$requete = $pdo->prepare("UPDATE membres SET
		hash_validation = ''
		WHERE
		hash_validation = :hash_validation");

	$requete->bindValue(':hash_validation', $hash_validation);
	
	$requete->execute();

	return ($requete->rowCount() == 1);
}

function combinaison_connexion_valide($nom_utilisateur, $mot_de_passe) {

	$pdo = PDO2::getInstance();

	$requete = $pdo->prepare("SELECT id FROM membres
		WHERE
		nom_utilisateur = :nom_utilisateur AND 
		mot_de_passe = :mot_de_passe AND
		hash_validation = ''");

	$requete->bindValue(':nom_utilisateur', $nom_utilisateur);
	$requete->bindValue(':mot_de_passe', $mot_de_passe);
	$requete->execute();
	
	if ($result = $requete->fetch(PDO::FETCH_ASSOC)) {

		$requete->closeCursor();
		return $result['id'];
	}
	return false;
}

function lire_infos_utilisateur($id_utilisateur) {

	$pdo = PDO2::getInstance();

	$requete = $pdo->prepare("SELECT id, nom_utilisateur, mot_de_passe, adresse_email, avatar, date_inscription, hash_validation
		FROM membres
		WHERE
		id = :id_utilisateur");

	$requete->bindValue(':id_utilisateur', $id_utilisateur);
	$requete->execute();
	
	if ($result = $requete->fetch(PDO::FETCH_ASSOC)) {

		$requete->closeCursor();
		return $result;
	}
	return false;
}

function maj_email_membre($id_utilisateur,$adresse_email)
{

	$pdo = PDO2::getInstance();

	$requete = $pdo->prepare("UPDATE membres SET
		adresse_email = :adresse_email
		WHERE
		id = :id_utilisateur");

	$requete->bindValue(':id_utilisateur', $id_utilisateur);
	$requete->bindValue(':adresse_email', $adresse_email);

	if ($requete->execute()) 
	{
		return true;
	}
	return $requete->errorInfo();
}

function maj_mot_de_passe_membre($id_utilisateur,$mdp,$old_mdp)
{
	$pdo = PDO2::getInstance();

	$requete = $pdo->prepare("UPDATE membres SET
		mot_de_passe = :mot_de_passe
		WHERE
		id = :id_utilisateur
		AND 
		mot_de_passe= :old_mdp");

	$requete->bindValue(':id_utilisateur', $id_utilisateur);
	$requete->bindValue(':mot_de_passe', $mdp);
	$requete->bindValue(':old_mdp', $old_mdp);
	$requete->execute();
	return $requete->rowCount();
}

function get_all_pseudos()
{

	$pdo = PDO2::getInstance();
	$reponse = $pdo->query("SELECT id, nom_utilisateur FROM membres ORDER BY nom_utilisateur");
	$donnees = $reponse->fetchAll();
	
	return $donnees;
}