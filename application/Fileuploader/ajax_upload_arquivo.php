<?php
	include('../../config/db.php');
	include('class.fileuploader.php');
	require 'vendor/autoload.php';

	$isAfterEditing = false;

	$mes = date('m'); $ano = date('Y');

	if (!file_exists('../../uploads/arquivos/'.$_GET['id_orcamento'].'/'.$ano.'/'.$mes.'/')) {
		mkdir('../../uploads/arquivos/'.$_GET['id_orcamento'].'/'.$ano.'/'.$mes.'/', 0777, true);
	}

	$caminho = '../../uploads/arquivos/'.$_GET['id_orcamento'].'/'.$ano.'/'.$mes.'/';

	$name_file = $_FILES["files"]["name"][0];

	if (isset($_POST['fileuploader']) && isset($_POST['_editingg'])) {
		$isAfterEditing = true;
		$nome = $name_file;
	}else{
		$extensao = pathinfo($name_file, PATHINFO_EXTENSION);
		$nome = md5(microtime($name_file)).'.'.$extensao;
	}

	$FileUploader = new FileUploader('files', array(
		'limit' => null,
		'maxSize' => null,
		'fileMaxSize' => null,
		'extensions' => null,
		'required' => false,
		'uploadDir' => $caminho,
		'title' => ''.$nome.'',
		'replace' => $isAfterEditing,
		'listInput' => true,
		'files' => null
	));

	$upload = $FileUploader->upload();
	
	$arquivo_completo = DOMAIN.'/uploads/arquivos/'.$_GET['id_orcamento'].'/'.$ano.'/'.$mes.'/'.strtolower($nome);

	$dados 	= array($_GET['id_orcamento'], $_GET['id_item'], $arquivo_completo, $nome, $name_file, $name_file);
	$sql 	= $db->prepare("INSERT INTO orcamentos_arquivos (id_orcamento, id_item, arquivo, nome, nome_customizado, original) VALUES (?,?,?,?,?,?)");
	$sql->execute($dados);


	echo json_encode($upload);

	exit;