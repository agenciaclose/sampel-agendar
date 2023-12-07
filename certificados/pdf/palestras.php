<?php
	require_once __DIR__ . '/../../vendor/autoload.php';
	ini_set("pcre.backtrack_limit", "99999999999999999999999999999");
	include(__DIR__ . '/../config/config.php');

	$sql_inscricao = $db->prepare("SELECT pp.*, p.data_palestra FROM palestras_participantes AS pp
								INNER JOIN palestras AS p ON p.id = pp.id_palestra
								WHERE (pp.codigo = '".$_GET['codigo']."' OR pp.codigo = '".$_GET['codigo']."') AND pp.presenca = 'Sim'");
	$sql_inscricao->execute();

	if($sql_inscricao->rowCount() == 0) {
		header("Location: https://sampel.com.br/eventos/certificados/404");
		die();
	}else{
		$inscricao = $sql_inscricao->fetch(PDO::FETCH_ASSOC);

		$sql_update = $db->prepare("UPDATE `palestras_participantes` SET `certificado` = 'Sim' WHERE codigo = '".$inscricao['codigo']."'");
		$sql_update->execute();

		$sql_palestra = $db->prepare("SELECT * FROM palestras WHERE id = '".$inscricao['id_palestra']."'");
		$sql_palestra->execute();
		$palestra = $sql_palestra->fetch(PDO::FETCH_ASSOC);
	}
	
	try {

		$mpdf = new \Mpdf\Mpdf([
					'format' => 'A4-L',
					'orientation' => 'L',
					'keep_table_proportions' => false,
					'margin_left' => 8,
					'margin_right' => 8,
					'margin_top' => 25,
					'margin_bottom' => 20,
					'margin_header' => 0,
					'margin_footer' => 10,
					'img_dpi' => 300,
					'mirrorMargins' => true
				]);
		//PEGA O ARQUIVO MODELO

		ob_start();
		require_once __DIR__ . '/palestras/pdf.php';
		$pdf = ob_get_clean();
		//echo $pdf;

		//GERA O PDF
		$mpdf->simpleTables = true;
		$mpdf->ignore_invalid_utf8 = true;
		$mpdf->mirrorMargins = 1;
		$mpdf->defaultfooterline = 0;
		$mpdf->WriteHTML($pdf);

		$mpdf->Output();


	} catch (\Mpdf\MpdfException $e) {
		echo $e->getMessage();
	}