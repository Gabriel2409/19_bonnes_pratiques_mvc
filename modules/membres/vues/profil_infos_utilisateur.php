<h2>Profil de <?php echo htmlspecialchars($nom_utilisateur); ?></h2>

<p>
	<img class="flottant_droite" src="<?php echo $avatar; ?>" title="Avatar de <?php echo htmlspecialchars($nom_utilisateur); ?>" />
	<span class="label_profil">Adresse email</span> : <?php echo htmlspecialchars($adresse_email); ?><br />
	<span class="label_profil">Date d'inscription</span> : <?php echo htmlspecialchars($date_inscription); ?>

	<?php 
	if (!empty($_SESSION) AND $_SESSION['id']==htmlspecialchars($_GET['id']))
	{
		?>
		<p><a href="index.php?module=membres&amp;action=modifier_profil&amp;id=<?php echo $_SESSION['id']; ?>">Modifier mon profil</a></p>
		<?php
	}
	?>
</p>