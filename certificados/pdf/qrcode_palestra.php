<?php
	require_once __DIR__ . '/../../vendor/autoload.php';
	ini_set("pcre.backtrack_limit", "99999999999999999999999999999");
	include(__DIR__ . '/../config/config.php');

	$sql_palestra = $db->prepare("SELECT * FROM palestras WHERE id = '".$_GET['id']."'");
	$sql_palestra->execute();
	$palestra = $sql_palestra->fetch(PDO::FETCH_ASSOC);

	try {

		$mpdf = new \Mpdf\Mpdf([
					'format' => 'A4',
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
		require_once __DIR__ . '/qrcode_palestra/pdf.php';

		$pdf = ob_get_clean();
		//echo $pdf;


		$mpdf->simpleTables = true;
		$mpdf->ignore_invalid_utf8 = true;
		$mpdf->mirrorMargins = 1;
		$mpdf->defaultfooterline = 0;
		$mpdf->WriteHTML($pdf);

		$mpdf->Output();


	} catch (\Mpdf\MpdfException $e) {
		echo $e->getMessage();
	}