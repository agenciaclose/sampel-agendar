<html>
	<head>
		<meta charset="utf-8">
		<title>Certificados</title>
		<style type="text/css">
			body{
				background: url('https://sampel.com.br/eventos/certificados/pdf/eventos/certificado.png');
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
			$counter = 0;
			$sql_inscricao = $db->prepare("SELECT vi.*, v.data_visita, v.tipo FROM visitas_inscricoes AS vi
								INNER JOIN visitas AS v ON v.id = vi.id_visita
								WHERE v.id = '".$visita['id']."' ORDER BY vi.nome ASC");
			$sql_inscricao->execute();
			$inscricao_dados = $sql_inscricao->fetchAll(PDO::FETCH_ASSOC);
			foreach ($inscricao_dados as $inscricao){
		?>
			<div class="usuario">
				<div><h1><?php echo mb_strtoupper($inscricao['nome'], 'UTF-8'); ?></h1></div>
				<div class="user_info"><?php echo formatarCPF($inscricao['cpf']); ?> / <?php echo $inscricao['cidade']; ?>,<?php echo $inscricao['estado']; ?></div>
			</div>

			<div class="visita">
				<div><b>Data da Visita:</b> <?php echo formatarData($inscricao['data_visita']); ?></div>
				<div><b>Local:</b> Planta Sampel</div>
				<div><b>Duração da Visita:</b> 06:00h</div>
			</div>
			<?php
				 if( $counter != count( $inscricao_dados ) - 1) {
					echo '<pagebreak />';
				}
				$counter = $counter + 1;
			?>
			
		<?php } ?>

	</body>
</html>