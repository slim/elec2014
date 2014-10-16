<?php
require_once "../ini.php";
if (!empty($_GET['q'])) {
	try {
		$q = $db->prepare('SELECT * from suggestions where texte like ?');
		$q->bindValue(1, '%'.$_GET['q'].'%');
		if ($q->execute()) $resultat = $q->fetchAll();
		die(json_encode($resultat));
	} catch (Exception $e) {
		fatal_error($e->getMessage());
	}
} else {
	die('{"isError":true}');
}
