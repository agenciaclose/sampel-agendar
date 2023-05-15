$(document).ready(function () {

	$('#servico_post').submit(function(e){
		e.preventDefault();
		var domain = $('body').data('domain');
		var formData = new FormData(this);
		$.ajax({
			url: domain + '/painel/servicos/save',
			data: formData,
			type: 'POST',
			success: function(data){
				if (data === '') {
					Swal.fire({
						type: 'warning',
						title: 'ERRO AO CADASTRAR!',
						text: 'Erro ao solicitar o saque. Tente novamente mais tarde ou nos contate no suporte',
						showConfirmButton: true
					});
				}else{
					Swal.fire({
						type: 'info',
						title: 'CADASTRADO COM SUCESSO!',
						text: 'Seu cadastro foi realizado com sucesso.',
						showConfirmButton: true,
						onClose: function () {
							window.location.href = domain + '/painel/servicos/edit/'+data;
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