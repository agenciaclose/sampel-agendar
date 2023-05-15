$(document).ready(function () {
	$('.repeater').repeater({
		repeaters: [{
			selector: '.inner-repeater'
		}]
	});

	$('.novo_produto').focusout(function(e){

		e.preventDefault();
		var title = $(this).val();
		var slug = format_slug(title, '');

		if(title != ''){
			var domain = $('body').data('domain');
			$.ajax({
				url: domain + '/painel/produtos/save_draft',
				data: {'title': title, 'slug': slug},
				type: 'POST',
				success: function(data){
					$('#produto_id').val(data.id);
					$('#slug-show').html(data.id+'/<span id="slug_link">'+data.slug+'</span>');
					$('#product_title').removeClass('novo_produto');
					$('#product_title').addClass('editar_produto');

					var new_url = domain+'/painel/produtos/edit/'+data.id;
					window.history.pushState('data','Title', new_url);
					document.title = 'Editar: '+data.title;

					$('.edit-slug-box').show();
					start_product_gallery();
				}
			});
		}
	});

	$('#produto_post').submit(function(e){
		$(this).children(':input[value=""]').attr("disabled", "disabled");
		var domain = $('body').data('domain');
		$('#salvar').prop('type', 'button');
		$('#salvar').addClass('disabled');
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: domain + '/painel/produtos/update',
			data: formData,
			type: 'post',
			success: function(data){
				if (data != '') {
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

function deleteProduct(id_produto) {
	swal({
		title: "Deletar esse Produto?",
		text: "Tem certeza que deseja excluir esse produto. Essa ação não poderá ser desfeita.",
		showCancelButton: true,
		cancelButtonText: 'Não',
		confirmButtonText: 'Sim',
		dangerMode: true,
	}).then((result) => {
		  if (result.value === true) {
			deleteProductAction(id_produto);
			Swal.fire('', 'Produto excluido com sucesso!', 'success');
		  }
	});
}

function deleteProductAction(id_produto){
	let domain = $('body').data('domain');
	$.ajax({
		url: domain + '/painel/produtos/excluir',
		data: {'id_produto': id_produto},
		type: 'post',
		success: function(data){
			$('.produto-'+id_produto).fadeOut();
		}
	});
}


function deleteFile(id_file, id_produto, file) {
	let domain = $('body').data('domain');
	$.ajax({
		url: domain + '/painel/produtos/delete-file',
		data: {'id_produto': id_produto, 'file': file},
		type: 'post',
		success: function(data){
			$('.arquivo-'+id_file).fadeOut();
		}
	});
}