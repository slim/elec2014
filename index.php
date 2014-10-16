<?php
require_once "ini.php";
if ($_GET['q']) {
	try {
		$q = $db->prepare('SELECT * from MainTable where NomCandidat like ?');
		$q->bindValue(1, '%'.$_GET['q'].'%');
		if ($q->execute()) $resultat = $q->fetchAll();
	} catch (Exception $e) {
		fatal_error($e->getMessage());
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>الانتخابات التشريعية تونس 2014</title>
		<link href="http://getbootstrap.com/2.3.2/assets/css/bootstrap.css" rel="stylesheet">
		<link href="style.css" rel="stylesheet">
</head>
<body dir="rtl">
<div class="container row-fluid">
<h1>التشريعية 2014</h1>
<form action="." method="get">
<p>أدخل اسم مترشح او دائرة أو قائمة</p>
<div class='input-prepend' dir='ltr'>
<button type='submit' class='btn btn-info'>ابحث</button>
<input type='text' name='q' placeholder='مثلا: بحري جلاصي، الجبهة، الكاف' dir='rtl'/>
</div>
</form>
<table class="table table-bordered">
<thead><tr><th>دائرة</th><th>قائمة</th><th>مرشح</th></tr></thead>
<tbody>
<?php 
		if (!$resultat) print "<div class='alert alert-error'>Aucun résultat</div>";
		else foreach ($resultat as $candidat) {
			$circo = $candidat['Circonscription'];
			$liste = $candidat['NomListe'];
			$nom = $candidat['NomCandidat'];
			print "<tr><td>$circo</td><td>$liste</td><td>$nom</td></tr>";
		}

?>
</tbody>
</table>
<small class="muted pull-right" style="position:fixed; right:5px; bottom:5px;">Contact <a href="https://twitter.com/slim404">@slim404</a> source code <a href="https://github.com/PPTN/mounachid">https://github.com/PPTN/mounachid</a></small>
</div>
</body>
</html>
