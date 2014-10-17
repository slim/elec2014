<?php
require_once "ini.php";
if (!empty($_GET['q'])) {
	try {
		$q = $db->prepare('SELECT * from MainTable where NomCandidat like ? or NomListe like ? or Circonscription like ?');
		$q->bindValue(1, '%'.trim($_GET['q']).'%');
		$q->bindValue(2, '%'.trim($_GET['q']).'%');
		$q->bindValue(3, '%'.trim($_GET['q']).'%');
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
		<script src="http://getbootstrap.com/2.3.2/assets/js/jquery.js"></script>
		<script src="http://getbootstrap.com/2.3.2/assets/js/bootstrap-typeahead.js"></script>
</head>
<body dir="rtl">
<div class="container row-fluid">
<h1>التشريعية 2014</h1>
<form action="." method="get">
<p>أدخل اسم مترشح او دائرة أو قائمة</p>
<div class='input-prepend' dir='ltr'>
<button type='submit' class='btn btn-info'>ابحث</button>
<input type='text' id='q' name='q' placeholder='مثلا: بحري جلاصي، الجبهة، الكاف' dir='rtl' autocomplete='off' />
<br /><small class="row muted pull-right" onclick="">ما عنديش لوحة مفاتيح بالعربي </small>
</div>
</form>
<div class="row offset4 span4">
<?php if (!empty($_GET['q'])) if (!$resultat) {
		print "<div class='alert alert-error'>Aucun résultat</div>"; 
} else { 
		$candidat = next($resultat);
		$circo = $candidat['Circonscription'];
		$liste = $candidat['NomListe'];
		$nom = $candidat['NomCandidat'];
		print "<table class='table table-bordered'><thead><tr><th>$liste <span class='label label-info'>$circo</span></th></tr></thead><tbody>";
		print "<tr><td>$nom</td></tr>";
		$nouvelle_liste = FALSE;
		foreach ($resultat as $candidat) {
			if ($circo != $candidat['Circonscription']) {
				$circo = $candidat['Circonscription'];
				$nouvelle_liste = TRUE;
			}
			if ($liste != $candidat['NomListe']) {
				$liste = $candidat['NomListe'];
				$nouvelle_liste = TRUE;
			}
			if ($nouvelle_liste) {
				print "</tbody></table><table class='table table-bordered'><thead><tr><th>$liste <span class='label label-info'>$circo</span></th></tr></thead><tbody>";
				$nouvelle_liste = FALSE;
			}
			$nom = $candidat['NomCandidat'];
			print "<tr><td>$nom</td></tr>";
		}
		print "</tbody></table>";

} ?>
</div>
</div>
<small class="row muted pull-right">Contact <a href="https://twitter.com/slim404">@slim404</a> & <a href="https://twitter.com/astrubaal">@Astrubaal</a> source <a href="https://github.com/slim/elec2014">https://github.com/slim/elec2014</a></small>
<script>
$(function() {
		$('#q').focus();
		$('#q').typeahead({
			source:function (query, process) {
				articles = [];
				
				$.getJSON('./json/', {q: query}, 
					function (data) {
						$.each(data, function (i, article) {
							articles.push(article.texte);
						});
					 
						process(articles);
					});
			},
			updater: function (item) {
				document.location.href='./?q='+item;
				return item;
			},
			highlighter: function(item) {
				return item;
			}
		});	
});
</script>
<!-- YAMLI CODE START -->
<script type="text/javascript" src="http://api.yamli.com/js/yamli_api.js"></script>
<script type="text/javascript">
  if (typeof(Yamli) == "object" && Yamli.init( { uiLanguage: "fr" , startMode: "offOrUserDefault" } ))
  {
    Yamli.yamlify( "q", { settingsPlacement: "bottomRight" } );
  }
</script>
<!-- YAMLI CODE END -->
</body>
</html>
