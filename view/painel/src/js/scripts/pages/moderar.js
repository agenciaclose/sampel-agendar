$(function(){

	$('#moderate_pedido').submit(function(e){
		var DOMAIN = $('body').data('domain');
		$('#salvar').prop('type', 'button');
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: DOMAIN + '/painel/pedidos/moderate/save',
			data: formData,
			type: 'POST',
			success: function(data){
				swal({type: 'success',title: 'SALVO COM SUCESSO!',showConfirmButton: false,timer: 1500});
				setTimeout(function() {location.reload();}, 1500);
			},
			processData: false,
			cache: false,
			contentType: false
		});
	});
	
});