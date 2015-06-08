<h2>Modification de votre profil utilisateur (<?php echo htmlspecialchars($_SESSION['pseudo']); ?>)</h2>

<?php

if (!empty($email_confirm)) {

	echo '<ul>'."\n";

	foreach($email_confirm as $m) {

		echo '	<li>'.$m.'</li>'."\n";
	}

	echo '</ul>';
}

if (!empty($erreurs_form_modif_email)) {

	echo '<ul>'."\n";

	foreach($erreurs_form_modif_email as $e) {

		echo '	<li>'.$e.'</li>'."\n";
	}

	echo '</ul>';
}

// $form_modif_infos->fieldsets("Modification de l'e-mail et de l'avatar", array('adresse_email', 'suppr_avatar', 'avatar'));

if (!$email_confirm) echo $form_modif_email;

if (!empty($avatar_confirm)) {

	echo '<ul>'."\n";

	foreach($avatar_confirm as $m) 
	{

		echo '	<li>'.$m.'</li>'."\n";
	}

	echo '</ul>';
}

if (!empty($erreurs_form_modif_avatar)) 
{

	echo '<ul>'."\n";

	foreach($erreurs_form_modif_avatar as $e) 
	{

		echo '	<li>'.$e.'</li>'."\n";
	}

	echo '</ul>';
}

// $form_modif_infos->fieldsets("Modification de l'e-mail et de l'avatar", array('adresse_email', 'suppr_avatar', 'avatar'));

if (!$avatar_confirm) echo $form_modif_avatar;


if (!empty($mdp_confirm)) {

	echo '<ul>'."\n";

	foreach($mdp_confirm as $m) 
	{

		echo '	<li>'.$m.'</li>'."\n";
	}

	echo '</ul>';
}






if (!empty($erreurs_form_modif_mdp)) 
{

 	echo '<ul>'."\n";

	foreach($erreurs_form_modif_mdp as $e) {

		echo '	<li>'.$e.'</li>'."\n";
	}

	echo '</ul>';
}

// $form_modif_mdp->fieldsets("Modification du mot de passe", array('mdp_ancien', 'mdp', 'mdp_verif'));

if (!$mdp_confirm) echo $form_modif_mdp;