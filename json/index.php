<?php
require_once "../ini.php";
if (!empty($_GET['q'])) {
	try {
		$search = '%'.trim($_GET['q']).'%';
		$search = str_replace(array('آ','ا','أ','ؤ','ء','ئ','و','إ','لإ','لأ'),'_',$search);
		$search = str_replace(array('و'),'%',$search);
		$q = $db->prepare('SELECT distinct texte from suggestions where texte like ?');
		$q->bindValue(1, $search);
		if ($q->execute()) $resultat = $q->fetchAll();
		die(json_encode($resultat));
	} catch (Exception $e) {
		fatal_error($e->getMessage());
	}
} else {
	die('{"isError":true}');
}
