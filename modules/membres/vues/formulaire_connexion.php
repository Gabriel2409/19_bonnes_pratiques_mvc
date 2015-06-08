<h2>Connexion au site</h2>

<p>Si vous n'êtes pas encore inscrit, vous pouvez le faire en <a href="index.php?module=membres&amp;action=inscription">cliquant sur ce lien</a>.</p>

<?php

if (!empty($erreurs_connexion)) {

	echo '<ul>'."\n";
	
	foreach($erreurs_connexion as $e) {
	
		echo '	<li>'.$e.'</li>'."\n";
	}
	
	echo '</ul>';
}

echo $form_connexion;