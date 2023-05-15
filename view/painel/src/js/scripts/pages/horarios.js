$(document).ready(function () {

	$('#horarios').submit(function(e){
		e.preventDefault();
		var domain = $('body').data('domain');
		var formData = new FormData(this);
		$.ajax({
			url: domain + '/painel/servicos/horarios/save',
			data: formData,
			type: 'POST',
			success: function(data){
				if (data === '') {
					Swal.fire({
						type: 'warning',
						title: 'ERRO AO SALVAR!',
						text: 'Erro ao solicitar o saque. Tente novamente mais tarde ou nos contate no suporte',
						showConfirmButton: true
					});
				}else{
					Swal.fire({
						type: 'info',
						title: 'SALVO COM SUCESSO!',
						text: 'Sua solicitação foi realizado com sucesso e estamos processando o saque para sua conta selecionada',
						showConfirmButton: true,
						onClose: function () {
							window.location.href = domain + '/painel/servicos/horarios';
						}
					});
				}
			},
			processData: false,
			cache: false,
			contentType: false
		});
	});

});