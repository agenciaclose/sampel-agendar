$(document).ready(function () {

	var accountUploadImg = $('#account-upload-img'),
	accountUserImage = $('.uploadedAvatar'),
	accountUploadBtn = $('#account-upload'),
	accountResetBtn = $('#account-reset');

	if (accountUserImage) {
		var resetImage = accountUserImage.attr('src');
		accountUploadBtn.on('change', function (e) {
			var reader = new FileReader(),
			files = e.target.files;
			reader.onload = function () {
				if (accountUploadImg) {
					accountUploadImg.attr('src', reader.result);
				}
			};
			reader.readAsDataURL(files[0]);
		});

		accountResetBtn.on('click', function () {
			accountUserImage.attr('src', resetImage);
		});
	}

	$('#editar-consultor-form').submit(function(e){

		var domain = $('body').data('domain');
		$('#salvar').prop('type', 'button');
		$('#salvar').addClass('disabled');
		e.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: domain + '/painel/curriculum/account',
			data: formData,
			type: 'POST',
			success: function(data){
				if (data == '') {
					swal({type: 'success',title: 'SALVO COM SUCESSO!',showConfirmButton: false,timer: 2000});
					setTimeout(function() {location.reload();}, 2000);
				}else{
					$('#salvar').prop('type', 'submit');
					$('#salvar').removeClass('disabled');
					swal({type: 'error',title: 'ERRO AO SALVAR!',showConfirmButton: false,timer: 2000});
				}
			},
			processData: false,
			cache: false,
			contentType: false
		});
	});

});
