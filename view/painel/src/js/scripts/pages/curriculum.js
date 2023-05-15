$(document).ready(function () {

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


	$('#editar-consultor-password').submit(function(e){

		var domain = $('body').data('domain');
		$('#salvar').prop('type', 'button');
		$('#salvar').addClass('disabled');
		e.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: domain + '/painel/curriculum/security',
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

	$('#editar-consultor-termos').submit(function(e){

		var domain = $('body').data('domain');
		$('#salvar').prop('type', 'button');
		$('#salvar').addClass('disabled');
		e.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: domain + '/painel/curriculum/terms',
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

function cContent(content){

	$('.curriculum-nav li a').removeClass('active');
	$('.curriculum-nav li .nav-'+content).addClass('active');

	$('.curriculum-content').hide();
	$('#'+content).show();

}