<?php 
	try {
		$db = new PDO('mysql:host=177.234.145.178;dbname=sampel_evento', 'sampel_evento', 'oG7ElprDRWDiRWNAEL');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		if($e->getCode() == 1049){
			echo "Banco de dados errado.";
		}else{
			echo $e->getMessage();
		}
	}