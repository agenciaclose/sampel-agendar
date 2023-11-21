<?php
	require_once __DIR__ . '/../../vendor/autoload.php';
	ini_set("pcre.backtrack_limit", "99999999999999999999999999999");
	include(__DIR__ . '/../config/config.php');

	$dados = array($_GET['codigo'], 'Sim');
	$sql_inscricao = $db->prepare("SELECT vi.*, v.data_visita FROM visitas_inscricoes AS vi
								INNER JOIN visitas AS v ON v.id = vi.id_visita
								WHERE vi.codigo = ? AND vi.presenca = ?");
	$sql_inscricao->execute($dados);
	if($sql_inscricao->rowCount() == 0) {
		header("Location: https://sampel.com.br/eventos/certificados/404");
		die();
	}
	$inscricao = $sql_inscricao->fetch(PDO::FETCH_ASSOC);
	
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
		require_once __DIR__ . '/visita/pdf.php';
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