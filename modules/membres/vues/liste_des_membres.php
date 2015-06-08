<h2>Liste des membres inscrits</h2>


<?php
foreach ($all_pseudos as $e)
{
	?>
	<p><a href="index.php?module=membres&amp;action=afficher_profil&amp;id=<?php echo $e['id'] ?>"><?php echo $e['nom_utilisateur']; ?></a></p>
	<?php
}
?>