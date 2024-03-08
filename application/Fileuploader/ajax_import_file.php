<?php
	include('../../config/db.php');
	include('class.fileuploader.php');
	require 'vendor/autoload.php';

	$sql_visita = $db->prepare("SELECT * FROM visitas WHERE id = '".$_GET['id_visita']."' LIMIT 1");
	$sql_visita->execute();
	$visita = $sql_visita->fetch(PDO::FETCH_ASSOC);

	$isAfterEditing = false;

	$mes = date('m');
	$ano = date('Y');

	if (!file_exists('../../uploads/visitas/'.$visita['id'].'/'.$ano.'/'.$mes.'/')) {
		mkdir('../../uploads/visitas/'.$visita['id'].'/'.$ano.'/'.$mes.'/', 0777, true);
	}

	$caminho = '../../uploads/visitas/'.$visita['id'].'/'.$ano.'/'.$mes.'/';

	if (isset($_POST['fileuploader']) && isset($_POST['_editingg'])) {
		$isAfterEditing = true;
		$nome     		= $_POST['_namee'];
	}else{
		$extensao 		= pathinfo($_POST['_namee'], PATHINFO_EXTENSION);
		$nome     		= md5(microtime($_POST['_namee'])).'.jpg';
	}

	$codigo = substr($_POST['_namee'],0,-4);

	$cod = explode('_', $codigo);
	$codigo_produto = $cod[0];

	if(!empty($visita['id'])){

		$FileUploader = new FileUploader('files', array(
			'limit' => null,
			'maxSize' => null,
			'fileMaxSize' => null,
			'extensions' => null,
			'required' => false,
			'uploadDir' => $caminho,
			'title' => ''.$nome.'',
			'replace' => $isAfterEditing,
			'editor' => array(
	            'maxWidth' => 1000,
	            'maxHeight' => 1000,
	            'crop' => true,
	            'quality' => null
			),
			'listInput' => true,
			'files' => null
		));

		if (isset($_POST['fileuploader']) && isset($_POST['_editingg'])) {}else{
			
			$foto_completa	= DOMAIN.'/uploads/visitas/'.$visita['id'].'/'.$ano.'/'.$mes.'/'.strtolower($nome);
			$dados = array($visita['id'], $foto_completa, $nome, $_POST['_namee']);
			$sql = $db->prepare("INSERT INTO visitas_imagens (id_visita, imagem, nome, original) VALUES (?,?,?,?)");
			$sql->execute($dados);

		}

		$upload = $FileUploader->upload();

		echo json_encode($upload);

	}else{
		echo '{"hasWarnings":false,"isSuccess":true,"warnings":[],"files":[{"date":"Wed, 09 Oct 2019 13:29:16 -0300","editor":true,"extension":"jpg","file":"..\/..\/..\/..\/cms\/produtos\/ampri\/2019\/10\/662ae75fb853f3d9ec2b3c7e5f8e64f5.jpg","name":"662ae75fb853f3d9ec2b3c7e5f8e64f5.jpg","old_name":"9105_B.JPG","old_title":"9105_B","replaced":false,"size":22635,"size2":"22.10 KB","title":"662ae75fb853f3d9ec2b3c7e5f8e64f5","type":"image\/jpeg","uploaded":true}]}';
	}


	exit;
