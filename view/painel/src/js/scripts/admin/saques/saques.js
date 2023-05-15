$(document).ready(function () {

    $('#add_save_saque_status').submit(function(e){

		e.preventDefault();
		var domain = $('body').data('domain');
		var formData = new FormData(this);
		$('#salvar').prop('type', 'button');

		$.ajax({
			url: domain + '/painel/admin/saques/statusSave',
			data: formData,
			type: 'POST',
			success: function(data){
				if (data === '0') {
					Swal.fire({
						type: 'info',
						title: 'ALTERADO COM SUCESSO!',
						text: '',
						showConfirmButton: true,
						onClose: function () {
							window.location.href = domain + '/painel/admin/saques';
						}
					});
				}else{
					$('#salvar').prop('type', 'submit');
					Swal.fire({
						type: 'warning',
						title: 'ALGO DEU ERRADO',
						text: 'Fale com o suporte',
						showConfirmButton: true
					});
				}
			},
			processData: false,
			cache: false,
			contentType: false
		});
	});


});