<html>
	<head>
		<meta charset="utf-8">
		<title>QRCode</title>
	</head>
	<body>

	<style type="text/css">
		.qrcode_inscricao .frame-color { fill: #246CB1 !important; }
		.qrcode_feedback .frame-color { fill: #79a722 !important; }
	</style>

	<div class="container">

		<div class="row">
			<div class="col-md-6">
				<div class="d-flex align-items-center qrcode_inscricao" style="min-height:100vh;">
					<?php echo $palestra['qrcode_inscricao']; ?>
				</div>
			</div>

			<div class="col-md-6">
				<div class="d-flex align-items-center qrcode_feedback" style="min-height:100vh;">
					<?php echo $palestra['qrcode_feedback']; ?>
				</div>
			</div>
		</div>

	</div>

	</body>
</html>