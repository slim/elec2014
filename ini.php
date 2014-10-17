<?php
	error_reporting(E_ALL);
	try {
		$db = new PDO("sqlite:".dirname(__FILE__)."/Listes-election2014.sqlite");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {
		fatal_error($e->getMessage());
	}

	function fatal_error($message)
	{
	    die("<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$message</div>");
	}

	class ListeElectorale 
	{
		public $nom;
		public $circo;
		public $candidats;

		function __toString()
		{
			$xml = "<table class='table table-bordered'><thead><tr><th> ".$this->nom." <span class='label label-info'> ".$this->circo." </span></th></tr></thead><tbody>";
			foreach ($this->candidats as $c) {
				$xml .= "<tr><td>".$c."</td></tr>";
			}
			$xml .= "</tbody></table>";
			return $xml;
		}

		static function charger($liste,$circo)
		{
			global $db;

			try {
				$q = $db->prepare('SELECT * from MainTable where NomListe=? and Circonscription=?');
				$q->bindValue(1, $liste);
				$q->bindValue(2, $circo);
			} catch (Exception $e) {
				fatal_error($e->getMessage());
			}
			$le = new ListeElectorale;
			$le->nom = $liste;
			$le->circo = $circo;
			if ($q->execute()) while ($candidat = $q->fetch()) {
				$le->candidats[$candidat['Ordre']] = $candidat['NomCandidat'];
			}
			return $le;
		}
	}
