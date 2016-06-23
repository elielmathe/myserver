<?php
	$add;$port;
	$add = "0.0.0.0";
	$port = 2020;
	if(!empty($_GET['addr'])){
		$add = $_GET['addr'];
	}
	if(!empty($_GET['port'])){
		$port = (int)$_GET['port'];
	}
	
	lancerEcoute($add, $port);
	
	function lancerEcoute($adresse,$port){
		$connexion = stream_socket_server("tcp://".$adresse.":".$port);
		while($socket = stream_socket_accept($connexion)){
			$recu = stream_socket_recvfrom($socket,1500,0);
			if(false === empty($recu)){
				//echo $recu."\r\n";
				traiterRequete($socket,$recu);
			}
		}
		stream_socket_shutdown($conn, STREAM_SHUT_RDWR);
	}
	
	function traiterRequete($socket,$recu){
		$donnees = split(":",$recu);
		if($recu == "la:UI83"){
			$val = split(":",$recu);
			$cmd = verifierLaCmd($val[1]);
			envoyerReponse($socket,$cmd);
		}else if($donnees[0] == "recu"){
			//echo ">>>WELCOME<<<<!\n";
			marquerRecu($donnees[1]);//On devra y passer en parametre les commandes recues
		}else if($donnees[0] == "traite"){
			
			marquerTraite($donnees[1]);//On devra y passer en parametre les commandes traites
		}
	}
	
	function verifierLaCmd($codeEquipement){
		include_once("app/app1.php");
		return verifierCmd($codeEquipement);
	}
	
	function marquerRecu($don7nees){
		include_once("app/app1.php");
		return marquerRc($donnees);
	}
	
	function marquerTraite($expression){
		include_once("app/app1.php");
		return marquerTt($expression);
	}
	
	function envoyerReponse($socket,$reponse){
		@stream_socket_sendto($socket,$reponse,0,$peer);//VERIFIER ERREUR $peer : Undefined variable
		//echo "Reponse avec succes\r\n";
	}	
