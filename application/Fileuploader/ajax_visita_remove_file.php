<?php
	include('../../config/db.php');

	if (isset($_POST['file'])) {

		$sql_item = $db->prepare("SELECT * FROM visitas_imagens WHERE `id_visita` = '".$_GET['id_visita']."' AND nome = '".$_POST['file']."'");
		$sql_item->execute();
		$item = $sql_item->fetch(PDO::FETCH_ASSOC);

		$caminho = explode('-', $item['data']);
		$nome_arquivo_thumb = explode('.', $item['nome']);

		$diretorio = '../../uploads/visitas/'.$_GET['id_visita'].'/'.$caminho[0].'/'.$caminho[1].'/';
		$diretorio_thumbnail = '../../uploads/visitas_thumbnail/'.$_GET['id_visita'].'/'.$caminho[0].'/'.$caminho[1].'/';

		$file = $diretorio.$item['nome'];
		$file_thumbnail = $diretorio_thumbnail.$nome_arquivo_thumb[0].'.jpg';
		
		if(file_exists($file))
			unlink($file);
			unlink($file_thumbnail);

		$sql = $db->prepare("DELETE FROM `visitas_imagens` WHERE `id_visita` = '".$_GET['id_visita']."' AND id = '".$item['id']."'");
		$sql->execute();

	}

	echo $file;