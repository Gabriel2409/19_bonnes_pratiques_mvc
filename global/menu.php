<div id="menu">
	
	<h2>Menu</h2>
	
	<ul>
		<li><a href="index.php">Accueil</a></li>
	</ul>
	
	<h3>Espace membre</h3>
	<ul>
		<li><a href="index.php?module=membres&amp;action=liste_membres">Liste des membres</a></li>
	<?php
	if (!utilisateur_est_connecte()) 
	{ 
		?>
		
			<li><a href="index.php?module=membres&amp;action=inscription">Inscription</a></li>
			<li><a href="index.php?module=membres&amp;action=connexion">Connexion</a></li>
		

		<?php
	} 
	else 
	{ 
		?>
		
				<li><a href="index.php?module=membres&amp;action=afficher_profil&amp;id=<?php echo $_SESSION['id']; ?>">Mon profil</a>



			<li><a href="index.php?module=membres&amp;action=deconnexion">Déconnexion</a></li>
		
		<?php 
	} 
	?>
	</ul>	

</div>