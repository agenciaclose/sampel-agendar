<html>
	<head>
		<meta charset="utf-8">
		<title>Certificados</title>
		<style type="text/css">
			body{
				background: url('https://sampel.com.br/eventos/certificados/pdf/palestras/certificado.png');
				background-image-resize: 6;
				font-family: sans-serif;
			}
			.usuario {
				width: 500px;
				text-align: left;
				position: absolute;
				right: 10%;
				top: 22%;
			}
			.usuario h1 {
				margin: 0;
				text-transform: uppercase;
			}
			.user_info {
				margin-top: 5px;
				font-size: 15pt;
				display: inline-block;
			}
			.visita {
				position: absolute;
				left: 48%;
				bottom: 25%;
			}
		</style>
	</head>

	<body>

		<?php 
			setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
			$dataOriginal = $palestra['data_palestra'];
			$data = new DateTime($dataOriginal);
			$dataFormatada = strftime('%d de %B de %Y', $data->getTimestamp());

			$data1 = new DateTime($palestra['data_palestra']);
			$data2 = new DateTime($palestra['data_fim']);

			// Calcula a diferença
			$diferenca = $data1->diff($data2);

			// Formata a saída para horas e minutos
			$horas = $diferenca->h;
			$minutos = $diferenca->i;

			// Adiciona zero à esquerda se necessário
			$horasFormatadas = str_pad($horas, 2, "0", STR_PAD_LEFT);
			$minutosFormatados = str_pad($minutos, 2, "0", STR_PAD_LEFT);
		?>

		<div class="usuario">
			<div><h1><?php echo mb_strtoupper($inscricao['nome'], 'UTF-8'); ?></h1></div>
			<div class="user_info"><?php echo formatarCPF($inscricao['cpf']); ?> / <?php echo $inscricao['cidade']; ?>,<?php echo $inscricao['estado']; ?></div>
		</div>

		<div class="visita">
			<div><b>Data da Palestra:</b> <?php echo $dataFormatada; ?></div>
			<div><b>Local:</b> <?php echo $palestra['endereco']; ?>, <?php echo $palestra['numero']; ?><br><?php echo $palestra['bairro']; ?>, <?php echo $palestra['cidade']; ?>, <?php echo $palestra['estado']; ?></div>
			<div><b>Duração da Palestra:</b> <?php echo $horasFormatadas . ':' . $minutosFormatados; ?>h</div>
		</div>

	</body>
</html>