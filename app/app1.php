<?php
	function lireCmd(){
		try{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO("mysql:host=127.0.0.1;dbname=mayang","root","mysql",$pdo_options);
			$req = $bdd -> query("SELECT * FROM commande");
			while($res = $req -> fetch()){
				//echo "<pre>";
				//print_r($res);
				//echo "</pre>";
			}
		}catch(Exception $err){
			die("Une erreur ".$err -> getMessage());
		}
	}
	
	function obtEtat($id){
		try{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO("mysql:host=127.0.0.1;dbname=mayang","root","mysql",$pdo_options);
			$req = $bdd -> prepare("SELECT etat FROM commande WHERE id=?");
			$req -> execute(array($id));
			while($res = $req -> fetch()){
				$req -> closeCursor();
				return $res['etat'];
			}
		}catch(Exception $err){
			die("Une erreur ".$err -> getMessage());
		}
	}
	
	function marquerRc($expression){
		try{
			$lesId = split(";",$expression);
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO("mysql:host=127.0.0.1;dbname=mayang","root","mysql",$pdo_options);
			for($i=0;$i<count($lesId);$i++){
				//echo $lesId[$i]."\n";
				$req = $bdd -> prepare("UPDATE commande SET etat=3 WHERE etat=2 AND id=?");
				$res = $req -> execute(array($lesId[$i]));
				$req -> closeCursor();
				if($res > 0){
					//return true;
				}else{
					//return false;
				}
			}
			
		}catch(Exception $err){
			die("Une erreur ".$err -> getMessage());
		}
	}
	
	function marquerTt($expression){
		try{
			$lesId = split(";",$expression);
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO("mysql:host=127.0.0.1;dbname=mayang","root","mysql",$pdo_options);
			for($i=0;$i<count($lesId);$i++){
				//echo $lesId[$i]."\n";
				$req = $bdd -> prepare("UPDATE commande SET etat=4 WHERE etat=3 AND id=?");
				$res = $req -> execute(array($lesId[$i]));
				$req -> closeCursor();
				if($res > 0){
					//return true;
				}else{
					//return false;
				}
			}
			
		}catch(Exception $err){
			die("Une erreur ".$err -> getMessage());
		}
	}
	
	function verifierCmd($equipement){
		try{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO("mysql:host=127.0.0.1;dbname=mayang","root","mysql",$pdo_options);
			$req = $bdd -> prepare("SELECT id,code,parametre FROM commande WHERE idEq=? AND etat=1");
			$req -> execute(array($equipement));
			$chaine = ";";
			while($res = $req -> fetch()){
				$chaine .= $res['code'].":".$res['parametre'].":".$res['id'].";";
				//echo "\n ** ".$chaine." \n";
				$req1 = $bdd -> prepare("UPDATE commande SET etat=2 WHERE id=?");
				$req1 -> execute(array($res['id']));
				$req1 -> closeCursor();
			}
			$req -> closeCursor();
			return $chaine;
		}catch(Exception $err){
			die("Une erreur ".$err -> getMessage());
		}
	}
	
	
	
	function insererCmd($code,$parametre){
		$nbreAleatoire = rand(129433, 9434343343);
		try{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO("mysql:host=127.0.0.1;dbname=mayang","root","mysql",$pdo_options);
			$req = $bdd -> prepare("INSERT INTO commande(id,code,parametre,etat,reponse,rappel) VALUES('',?,?,1,'videX',?)");
			$res = $req -> execute(array($code,$parametre,$nbreAleatoire));
			$req -> closeCursor();
			if($res > 0){
				$req1 = $bdd -> prepare("SELECT id FROM commande WHERE rappel=?");
				$req1 -> execute(array($nbreAleatoire));
				if($res1 = $req1 -> fetch()){
					return $res1['id'];
				}else{
					return false;
				}
			}else{
				return false;
			}	
		}catch(Exception $err){
			die("Une erreur ".$err -> getMessage());
		}
	}
