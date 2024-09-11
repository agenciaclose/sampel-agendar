<?php
	include('../../config/db.php');

	if (isset($_POST['file'])) {

		$sql_item = $db->prepare("SELECT * FROM orcamentos_arquivos WHERE `id_orcamento` = '".$_GET['id_orcamento']."' AND `id_item` = '".$_GET['id_item']."' AND nome_customizado = '".$_POST['file']."'");
		$sql_item->execute();
		$item = $sql_item->fetch(PDO::FETCH_ASSOC);

		$caminho = explode('-', $item['data']);

		$diretorio = '../../uploads/arquivos/'.$_GET['id_orcamento'].'/'.$caminho[0].'/'.$caminho[1].'/';

		$file = $diretorio.$item['nome'];
		
		if(file_exists($file))
			unlink($file);

		$sql = $db->prepare("DELETE FROM `orcamentos_arquivos` WHERE `id_orcamento` = '".$_GET['id_orcamento']."' AND `id_item` = '".$_GET['id_item']."' AND id = '".$item['id']."'");
		$sql->execute();

	}

	echo $file;