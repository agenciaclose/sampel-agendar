<?php
	//require_once __DIR__ . '/../../vendor/autoload.php';
	include(__DIR__ . '/../config/config.php');

	ini_set("pcre.backtrack_limit", "99999999999999999999999999999");

	$empresa_id = $_POST['empresa'];

	$sql_empresa = $db->prepare("SELECT * FROM usuarios WHERE id = '".$empresa_id."'");
	$sql_empresa->execute();
	$empresa = $sql_empresa->fetch(PDO::FETCH_ASSOC);


	if( (!empty($_POST['catalogo_pdf'])) && ($_POST['catalogo_pdf'] != 'all') ){
		$catalogo_pdf = $_POST['catalogo_pdf'];
		$filename = $_POST['catalogo_pdf'];

	}else{
		$catalogo_pdf = '';
		$filename = $empresa['nome'];

	}

	if(!empty($_POST['list_name'])){

		$filename = $_POST['list_name'];

	}else{

		$filename = $empresa['nome'];

	}

	$sql_catalogos_impresso = $db->prepare("SELECT * FROM catalogos_impresso WHERE empresa = '".$empresa_id."'");
	$sql_catalogos_impresso->execute();
	$catalogos_impresso = $sql_catalogos_impresso->fetch(PDO::FETCH_ASSOC);


	if($catalogos_impresso['modelo'] != ''){
		$tema = $catalogos_impresso['modelo'];
	}else{
		$tema = 'tema1';
	}


	try {

		$mpdf = new \Mpdf\Mpdf([
							'format' => 'A4',
							'keep_table_proportions' => false,
							'margin_left' => 8,
							'margin_right' => 8,
							'margin_top' => 25,
							'margin_bottom' => 20,
							'margin_header' => 0,
							'margin_footer' => 0,
							'img_dpi' => 300,
						]);
		//PEGA O ARQUIVO MODELO

		ob_start();
		require_once __DIR__ . '/'.$tema.'/gerar.php';
		$pdf = ob_get_clean();
		//echo $pdf;

		// GERA O PDF
		$mpdf->ignore_invalid_utf8 = true;
		$mpdf->SetHTMLFooter('
			<table width="100%" class="footer">
				<tr>
					<td align="left">buscanarede.com.br</td>
					<td align="center" class="pagination">{PAGENO}/{nbpg}</td>
				</tr>
			</table>');
		$mpdf->WriteHTML($pdf);

		if(!empty($_POST['list_name'])){
			$mpdf->Output();
		}else{
			$mpdf->Output(__DIR__ . '/downloads/'.$_POST['id_file'].'.pdf', \Mpdf\Output\Destination::FILE);
		}

	} catch (\Mpdf\MpdfException $e) {
		echo $e->getMessage();
	}


?>