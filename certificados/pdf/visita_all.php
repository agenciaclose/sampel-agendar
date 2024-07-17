<?php
	require_once __DIR__ . '/../../vendor/autoload.php';
	ini_set("pcre.backtrack_limit", "1000000");
	include(__DIR__ . '/../config/config.php');

	$sql_visita = $db->prepare("SELECT * FROM visitas WHERE id = '".$_GET['id']."'");
	$sql_visita->execute();

	if($sql_visita->rowCount() == 0) {
		header("Location: https://sampel.com.br/eventos/certificados/404");
		die();
	}else{
		$visita = $sql_visita->fetch(PDO::FETCH_ASSOC);
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
		if($visita['tipo'] == 'evento') {
			require_once __DIR__ . '/eventos/pdf_all.php';
		}else{
			require_once __DIR__ . '/visita/pdf_all.php';
		}

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