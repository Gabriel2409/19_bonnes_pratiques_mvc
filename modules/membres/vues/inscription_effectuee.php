<h2>Inscription confirmée</h2>


<p>L'inscription s'est déroulée avec succès !</p>

<?php 
if (!empty($erreurs_avatar))
{
	echo '<p>Par contre, l\'upload de l\'avatar a échoué pour la raison suivante : '. $erreurs_avatar . '</p>';
}
?>
<p>Vous allez bientôt recevoir un mail vous permettant d'activer votre compte afin de pouvoir vous connecter.</p>

<p><a href="index.php">Revenir à la page d'accueil</a></p>