<?php
	
	for($i=1;$i>-1;$i++){
		$tps1 = tps();
		envoyerCommande("127.0.0.1",2020,"la:UI83");
		$tps2 = tps();
		echo "\t ".$i.". Execution en ".($tps2 - $tps1)." \n";
		sleep(2);
	}
		
	function envoyerCommande($adresse,$port,$donnee){
		//echo "0. Envoie de la commande\n";
		$socket = stream_socket_client("tcp://".$adresse.":".$port);
		if($socket){
			$envoie = stream_socket_sendto($socket,$donnee);
			if($donnee == "la:UI83"){
				if($envoie>0){
					$reponseServeur = fread($socket,4096);
					traiterDonneesRecus($reponseServeur);
					return $reponseServeur;
				}else echo "Echec d'envoie des fichiers";
			}else{
				//echo "4. Envoyer commandes auxiliaires!";
			}
		}else echo "Echec de la connexion a l'adresse ".$adresse;
	}
	
	function marquerCmdRecus($expression){
		//echo "2. Marquer commandes recues\n";
		$val = split(";",$expression);
		$chaine = "";
		for($i=0;$i<count($val);$i++){
			$id = split(":",$val[$i]);
			if(!empty($id[2])) $chaine .= $id[2].";";
		}
		$fichier = fopen("lesId.txt","a+");
		fwrite($fichier,$chaine);
		fclose($fichier);
		envoyerCmdRecu();
	}
	
	function envoyerCmdRecu(){
		//echo "3. Envoyer commandes recues\n";
		$fichier = fopen("lesId.txt","r");
		$lesId = "recu:";
		while($ligne = fread($fichier, 4096)){
			$lesId .= $ligne;
		}
		//Envoyer les id qui sont en cours de traitement.
		
		fclose($fichier);
		$fichier = fopen("lesId.txt","w+");
		fwrite($fichier," ");
		fclose($fichier);
		envoyerCommande("127.0.0.1",2020,$lesId);
		//traiterCmd();
	}
	
	function traiterDonneesRecus($donnees){
		//echo "1. traiter donnees recus\n";
		$fichier = fopen("duServ.txt","a+");
		fwrite($fichier, $donnees,strlen($donnees));
		fclose($fichier);
		marquerCmdRecus($donnees);
		
		//envoyerCommande("127.0.0.1",2020,"recu");
		traiterCmd();
	}
	
	function traiterCmd(){
		$fichier = fopen("duServ.txt","r");
		if($fichier){
			while($ligne = fread($fichier,3000)){
				decouper($ligne);
			}
		}
	}
	
	function decouper($expression){
		$valeurs = split(";",$expression);
		//echo "Nous avons >>>>>".count($valeurs)."<<<<<";
		for($i = 0;$i<count($valeurs);$i++){
			$clef = split(":",$valeurs[$i]);
			//echo $clef."\n";
			//print_r($clef);
			if($clef[0] == "318"){
				$rep = shell_exec("ping -c 4 ".$clef[1]);
				//echo $rep;
			}
		}
		$fichier = fopen("duServ.txt","w+");
		fwrite($fichier," ");
		fclose($fichier);
		//envoyerCommande("127.0.0.1",2020,"traite");
		//echo "\n =========== CMD SUCCESS ================";
	}
	
	function tps(){
		$temps = microtime();
		$temps1 = split(" ", $temps);
		$temps = $temps1[0] + $temps1[1];
		return $temps;
	}
