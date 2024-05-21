<?php
	include('../../config/db.php');

	if (isset($_POST['file'])) {

		$sql_item = $db->prepare("SELECT * FROM palestras_imagens WHERE `id_palestra` = '".$_GET['id_palestra']."' AND nome = '".$_POST['file']."'");
		$sql_item->execute();
		$item = $sql_item->fetch(PDO::FETCH_ASSOC);

		$caminho = explode('-', $item['data']);
		$nome_arquivo_thumb = explode('.', $item['nome']);

		$diretorio = '../../uploads/palestras/'.$_GET['id_palestra'].'/'.$caminho[0].'/'.$caminho[1].'/';
		$diretorio_thumbnail = '../../uploads/palestras_thumbnail/'.$_GET['id_palestra'].'/'.$caminho[0].'/'.$caminho[1].'/';

		$file = $diretorio.$item['nome'];
		$file_thumbnail = $diretorio_thumbnail.$nome_arquivo_thumb[0].'.jpg';
		
		if(file_exists($file))
			unlink($file);
			unlink($file_thumbnail);

		$sql = $db->prepare("DELETE FROM `palestras_imagens` WHERE `id_palestra` = '".$_GET['id_palestra']."' AND id = '".$item['id']."'");
		$sql->execute();

	}

	echo $file;