$(document).ready(function() {

	$('#pedidos').DataTable({
		"ordering": false,
        "lengthMenu": [25, 50, 100],
        "language": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "searchPlaceholder": "Pesquisar..",
            "sLengthMenu": "_MENU_",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        }
    });

    var table = $('#produtos').DataTable({
        "ordering": false,
        "bPaginate": false,
        "lengthMenu": [[100], [100]],
        "language": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "searchPlaceholder": "Pesquisar..",
            "sLengthMenu": "_MENU_",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        }
    });

    var originalContent = {};

    $('th.filter-column').on('click', function() {
        var $th = $(this);
        var columnIndex = $th.index();

        if (!originalContent[columnIndex]) {
            originalContent[columnIndex] = $th.html();
        }

        if ($th.find('input').length === 0) {
            var columnName = $th.text();
            $th.html('<input type="text" class="form-control form-control-sm" placeholder="' + columnName + '" />');
            $th.find('input').on('keyup change', function() {
                var val = $(this).val();
                table.column(columnIndex).search(val).draw();
            }).focus();
        }
    });

    $(document).on('click', function(event) {
        var $target = $(event.target);
        if (!$target.closest('th.filter-column').length && !$target.closest('input').length) {
            $('th.filter-column').each(function(index) {
                var $th = $(this);
                if ($th.find('input').length > 0) {
                    var inputVal = $th.find('input').val();
                    $th.html(originalContent[$th.index()]);
                    if (inputVal) {
                        $th.append(' (' + inputVal + ') <a class="reset-filter text-danger"><i class="fa-solid fa-lg fa-delete-left"></i></a>');
                    }
                }
            });
        }
    });

    $(document).on('click', '.reset-filter', function(event) {
        event.stopPropagation();
        var $button = $(this);
        var $th = $button.closest('th');
        var columnIndex = $th.index();

        $th.html(originalContent[columnIndex]);
        $th.html('<input type="text" class="form-control form-control-sm" placeholder="' + originalContent[columnIndex] + '" />');
        var $input = $th.find('input');
        $input.focus();

        var e = $.Event('keyup');
        e.keyCode = 8;
        $input.trigger(e);
        table.column(columnIndex).search('').draw();
        delete originalContent[columnIndex];
    });
});

$(document).ready(function(){
    $('input[type="number"]').on('keypress', function(e) {
        e.preventDefault();
    });
	if ($('.select2').length){
		$('.select2').select2();
	}
});

$(function(){

	$('#tipo_evento').on('change', function(e) {
		e.preventDefault();
		var DOMAIN = $('body').data('domain');
		var tipo = $(this).val();

		if(tipo == 'extra'){
			$('.id_evento').hide();
			$('.id_evento_select').removeAttr('required');
		}else{

			$.ajax({
				url: DOMAIN + '/painel/pedidos/tipo',
				data: {"tipo": tipo},
				type: 'POST',
				success: function(response) {
					try {
						if (typeof response === 'string') {
							response = response.substring(response.indexOf('['));
							response = JSON.parse(response);
						}
						if (Array.isArray(response)) {
							var select = $('.id_evento_select');
							select.empty();
							select.append('<option value="">Selecione</option>');
							response.forEach(function(item) {
								var formattedDate = item.data.split('-').reverse().join('/');
								var option = $('<option>', {
									value: item.id,
									text: formattedDate + ' - ' + item.title
								});
								select.append(option);
							});
						} else {
							console.error("Response is not an array");
						}
					} catch (error) {
						console.error("Failed to parse response as JSON", error);
					}
				},
				error: function(xhr, status, error) {
					console.error("AJAX request failed", status, error);
				}
			});

			$('.id_evento').show();
			$('.id_evento_select').attr('required', 'required');
		}
	});

	$('#novo_pedido').submit(function(e){
		var DOMAIN = $('body').data('domain');
		$('#salvar').prop('type', 'button');
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: DOMAIN + '/painel/pedidos/add/save',
			data: formData,
			type: 'POST',
			success: function(data){
				if (data == "1") {
					//swal({type: 'success',title: 'SALVO COM SUCESSO!',showConfirmButton: false,timer: 2000});
					//setTimeout(function() {window.location.href = DOMAIN + '/painel/pedidos';}, 2000);
				}else{
					//$('#salvar').prop('type', 'submit');
					//swal({type: 'error',title: 'ERRO AO SALVAR!',showConfirmButton: false,timer: 2000});
				}
			},
			processData: false,
			cache: false,
			contentType: false
		});
	});

	$('#form-editar').submit(function(e){
		$('#salvar').prop('type', 'button');
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: domain + 'assets/data/gestao-pedidos/editar.php',
			data: formData,
			type: 'post',
			success: function(data){
				if (data == "1") {
					swal({type: 'success',title: 'EDITADO COM SUCESSO!',showConfirmButton: false,timer: 2000});
					setTimeout(function() {window.location.href= domain + 'gestao-pedidos-lista/'+formData.get('daraj')+'';}, 2000);
				}else{
					$('#salvar').prop('type', 'submit');
					swal({type: 'error',title: 'ERRO AO EDITAR!',showConfirmButton: false,timer: 2000});
				}
			},
			processData: false,
			cache: false,
			contentType: false
		});
	});
	
});

function deletar(id, status_pedido){
	var DOMAIN = $('body').data('domain');
	if(status_pedido == 'Recusado'){
		Swal.fire({
			title: 'RECUSAR ESTE PEDIDO?',
			text: "Esta ação será permamente.",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: 'SIM',
			cancelButtonText: `NÃO`,
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type: 'POST',
					url: DOMAIN + '/painel/pedidos/add/status',
					data: {id:id, status_pedido:status_pedido},
					success: function () {
						Swal.fire({title: 'RECUSADO!',type: "success",showCancelButton: false});
						setTimeout(function() {location.reload();}, 2000);
					}
				});
			}
		});
	}else{
		$.ajax({
			type: 'POST',
			url: DOMAIN + '/painel/pedidos/add/status',
			data: {id:id, status_pedido:status_pedido},
			success: function () {
				swal("", "ALTERADO!", "success");
				setTimeout(function() {location.reload();}, 2000);
			}
		});
	}
}

function itemTotal(quantidade, valor, item, validation, unidades){

	var max = $('.itemMax-'+item+'').text();
	var qtd_total = 0;
	qtd_total = quantidade * unidades;

	if(parseInt(quantidade) > parseInt(max)){
		$('#cota-'+item+'').removeClass('bg-success');
		$('#cota-'+item+'').addClass('bg-danger');
		var itemTotal = (parseFloat(valor.replace('.','').replace(',','.')) * parseInt(qtd_total));
	}else{
		$('#cota-'+item+'').removeClass('bg-danger');
		$('#cota-'+item+'').addClass('bg-success');
		var itemTotal = (parseFloat(valor.replace('.','').replace(',','.')) * parseInt(qtd_total));
	}

	$('#qtd-total-'+item+'').html(qtd_total);
	$('#qtd-total-value-'+item+'').val(qtd_total);

	$('#itemTotal-'+item+'').html(numberToReal(itemTotal)); 
	$('#valor-itemTotal-'+item+'').val(itemTotal); 

	var valorTotal = 0;

	$(".valorItemTotal").filter(function() {
		var totalbase = $(this).text();
		valorTotal += parseFloat(totalbase.replace('.','').replace(',','.'));
	});
	 
	$('.total').html(numberToReal(valorTotal));

	// if(valorTotal >= 200){
	// 	$('#salvar').prop("disabled", false);
	// 	$('.aviso-minimo').removeClass('text-danger');
	// 	$('.aviso-minimo').addClass('text-success');
	// }else{
	// 	$('#salvar').prop("disabled", true);
	// 	$('.aviso-minimo').removeClass('text-success');
	// 	$('.aviso-minimo').addClass('text-danger');
	// }

};

function numberToReal(numero) {
    var numero = numero.toFixed(2).split('.');
    numero[0] = '' + numero[0].split(/(?=(?:...)*$)/).join('.');
    return numero.join(',');
}
