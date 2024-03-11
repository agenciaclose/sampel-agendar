<?php
	include('../../config/db.php');
	include('class.fileuploader.php');
	require 'vendor/autoload.php';

	$isAfterEditing = false;

	$sql_imagem = $db->prepare("SELECT * FROM visitas_imagens WHERE nome = '".$_POST['_namee']."'");
	$sql_imagem->execute();
	$imagem = $sql_imagem->fetch(PDO::FETCH_ASSOC);

	if (isset($_POST['fileuploader']) && isset($_POST['_editingg'])) {
		$data = explode('-', $imagem['data']);
		$mes = $data[1];
		$ano = $data[0];
	}else{
		$mes = date('m');
		$ano = date('Y');
	}

	if (!file_exists('../../uploads/visitas/'.$_GET['id_visita'].'/'.$ano.'/'.$mes.'/')) {
		mkdir('../../uploads/visitas/'.$_GET['id_visita'].'/'.$ano.'/'.$mes.'/', 0777, true);
	}

	$caminho = '../../uploads/visitas/'.$_GET['id_visita'].'/'.$ano.'/'.$mes.'/';

	if (isset($_POST['fileuploader']) && isset($_POST['_editingg'])) {
		$isAfterEditing = true;
		$nome     		= $_POST['_namee'];
	}else{
		$extensao 		= pathinfo($_POST['_namee'], PATHINFO_EXTENSION);
		$nome     		= md5(microtime($_POST['_namee'])).'.jpg';
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

	if (isset($_POST['fileuploader']) && isset($_POST['_editingg'])) {}else{

		$foto_completa	= DOMAIN.'/uploads/visitas/'.$_GET['id_visita'].'/'.$ano.'/'.$mes.'/'.strtolower($nome);
		$dados = array($_GET['id_visita'], $_SESSION['sampel_user_id'], $foto_completa, $nome, $_POST['_namee']);
		$sql = $db->prepare("INSERT INTO visitas_imagens (id_visita, id_user, imagem, nome, original) VALUES (?,?,?,?,?)");
		$sql->execute($dados);

	}

	$upload = $FileUploader->upload();

	if($upload){
	
			//THUMBNAIL
			$sql_imagens = $db->prepare("SELECT * FROM visitas_imagens ORDER BY id DESC LIMIT 1");
			$sql_imagens->execute();
			$dados_imagens = $sql_imagens->fetch(PDO::FETCH_ASSOC);

			$dados_imagens['imagem'] = str_replace(DOMAIN."/","../../",$dados_imagens['imagem']);
			$nome = explode('.', $dados_imagens['nome']);

			if (!file_exists('../../uploads/visitas_thumbnail/'.$_GET['id_visita'].'/'.$ano.'/'.$mes.'/')) {
				mkdir('../../uploads/visitas_thumbnail/'.$_GET['id_visita'].'/'.$ano.'/'.$mes.'/', 0777, true);
			}

			// create a new instance of the class
			$image = new Zebra_Image();
			$image->auto_handle_exif_orientation = false;

			$image->source_path = '../../uploads/visitas/'.$_GET['id_visita'].'/'.$ano.'/'.$mes.'/'.strtolower($dados_imagens['nome']);

			$image->target_path = '../../uploads/visitas_thumbnail/'.$_GET['id_visita'].'/'.$ano.'/'.$mes.'/'.$nome[0].'.jpg';

			$imagem_thumbnail = DOMAIN.'/'.substr($image->target_path, 14);
			$sql_update = $db->prepare("UPDATE `visitas_imagens` SET `thumbnail` = '".$imagem_thumbnail."' WHERE `id` = '".$dados_imagens['id']."'");
			$sql_update->execute();

			$image->jpeg_quality = 20;
			$image->preserve_aspect_ratio = true;
			$image->enlarge_smaller_images = true;
			$image->preserve_time = true;
			$image->handle_exif_orientation_tag = true;

			// resize the image to exactly 100x100 pixels by using the "crop from center" method
			if (!$image->resize(400, 400, ZEBRA_IMAGE_CROP_CENTER)) {};
		
	}

	echo json_encode($upload);

	exit;