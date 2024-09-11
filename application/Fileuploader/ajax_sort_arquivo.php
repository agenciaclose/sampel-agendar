<?php
	include('../../config/db.php');

    $list = isset($_POST['_list']) ? json_decode($_POST['_list'], true) : null;
   
	foreach ($list as $key => $item){

		$sql = $db->prepare("UPDATE `orcamentos_arquivos` SET `order` = '".$item['index']."' WHERE `id_orcamento` = '".$_GET['id_orcamento']."' AND `id_item` = '".$_GET['id_item']."' AND nome_customizado = '".$item['name']."'");
		$sql->execute();
		
	}