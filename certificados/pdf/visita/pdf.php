<html>
	<head>
		<meta charset="utf-8">
		<title>Certificados</title>
		<style type="text/css">
			body{
				background: url('http://localhost/evento/certificados/pdf/visita/certificado.png');
				background-image-resize: 6;
				font-family: sans-serif;
			}
			.usuario {
				width: 500px;
				text-align: center;
				position: absolute;
				right: 9%;
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

		<div class="usuario">
			<div><h1><?php echo mb_strtoupper($inscricao['nome'], 'UTF-8'); ?></h1></div>
			<div class="user_info"><?php echo formatarCPF($inscricao['cpf']); ?> / <?php echo $inscricao['cidade']; ?>,<?php echo $inscricao['estado']; ?></div>
		</div>

		<div class="visita">
			<div><b>Data da Visita:</b> <?php echo formatarData($inscricao['data_visita']); ?></div>
			<div><b>Local:</b> Planta Sampel</div>
			<div><b>Duração da Visita:</b> 06:00h</div>
		</div>

	</body>
</html>