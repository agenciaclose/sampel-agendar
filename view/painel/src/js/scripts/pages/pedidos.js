/* jquery.filterTable */
!function($){var e=$.fn.jquery.split("."),t=parseFloat(e[0]),i=parseFloat(e[1]);t<2&&i<8?($.expr[":"].filterTableFind=function(e,t,i){return $(e).text().toUpperCase().indexOf(i[3].toUpperCase().replace(/"""/g,'"').replace(/"\\"/g,"\\"))>=0},$.expr[":"].filterTableFindAny=function(e,t,i){var n=i[3].split(/[\s,]/),r=[];return $.each(n,function(e,t){var i=t.replace(/^\s+|\s$/g,"");i&&r.push(i)}),!!r.length&&function(e){var t=!1;return $.each(r,function(i,n){if($(e).text().toUpperCase().indexOf(n.toUpperCase().replace(/"""/g,'"').replace(/"\\"/g,"\\"))>=0)return t=!0,!1}),t}},$.expr[":"].filterTableFindAll=function(e,t,i){var n=i[3].split(/[\s,]/),r=[];return $.each(n,function(e,t){var i=t.replace(/^\s+|\s$/g,"");i&&r.push(i)}),!!r.length&&function(e){var t=0;return $.each(r,function(i,n){$(e).text().toUpperCase().indexOf(n.toUpperCase().replace(/"""/g,'"').replace(/"\\"/g,"\\"))>=0&&t++}),t===r.length}}):($.expr[":"].filterTableFind=jQuery.expr.createPseudo(function(e){return function(t){return $(t).text().toUpperCase().indexOf(e.toUpperCase().replace(/"""/g,'"').replace(/"\\"/g,"\\"))>=0}}),$.expr[":"].filterTableFindAny=jQuery.expr.createPseudo(function(e){var t=e.split(/[\s,]/),i=[];return $.each(t,function(e,t){var n=t.replace(/^\s+|\s$/g,"");n&&i.push(n)}),!!i.length&&function(e){var t=!1;return $.each(i,function(i,n){if($(e).text().toUpperCase().indexOf(n.toUpperCase().replace(/"""/g,'"').replace(/"\\"/g,"\\"))>=0)return t=!0,!1}),t}}),$.expr[":"].filterTableFindAll=jQuery.expr.createPseudo(function(e){var t=e.split(/[\s,]/),i=[];return $.each(t,function(e,t){var n=t.replace(/^\s+|\s$/g,"");n&&i.push(n)}),!!i.length&&function(e){var t=0;return $.each(i,function(i,n){$(e).text().toUpperCase().indexOf(n.toUpperCase().replace(/"""/g,'"').replace(/"\\"/g,"\\"))>=0&&t++}),t===i.length}})),$.fn.filterTable=function(e){var t={autofocus:!1,callback:null,containerClass:"filter-table",containerTag:"div",filterExpression:"filterTableFind",hideTFootOnFilter:!1,highlightClass:"alt",ignoreClass:"",ignoreColumns:[],inputSelector:null,inputName:"",inputType:"search",label:"",minChars:1,minRows:8,placeholder:"search this table",preventReturnKey:!0,quickList:[],quickListClass:"quick",quickListClear:"",quickListGroupTag:"",quickListTag:"a",visibleClass:"visible"},i=function(e){return e.replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/</g,"&lt;").replace(/>/g,"&gt;")},n=$.extend({},t,e),r=function(e,t){var i=e.find("tbody");if(""===t||t.length<n.minChars)i.find("tr").show().addClass(n.visibleClass),i.find("td").removeClass(n.highlightClass),n.hideTFootOnFilter&&e.find("tfoot").show();else{var r=i.find("td");if(i.find("tr").hide().removeClass(n.visibleClass),r.removeClass(n.highlightClass),n.hideTFootOnFilter&&e.find("tfoot").hide(),n.ignoreColumns.length){var a=[];n.ignoreClass&&(r=r.not("."+n.ignoreClass)),a=r.filter(":"+n.filterExpression+'("'+t+'")'),a.each(function(){var e=$(this),t=e.parent().children().index(e);$.inArray(t,n.ignoreColumns)===-1&&e.addClass(n.highlightClass).closest("tr").show().addClass(n.visibleClass)})}else n.ignoreClass&&(r=r.not("."+n.ignoreClass)),r.filter(":"+n.filterExpression+'("'+t+'")').addClass(n.highlightClass).closest("tr").show().addClass(n.visibleClass)}n.callback&&n.callback(t,e)};return this.each(function(){var e=$(this),t=e.find("tbody"),a=null,l=null,s=null,c=!0;if("TABLE"===e[0].nodeName&&t.length>0&&(0===n.minRows||n.minRows>0&&t.find("tr").length>=n.minRows)&&!e.prev().hasClass(n.containerClass)){if(n.inputSelector&&1===$(n.inputSelector).length?(s=$(n.inputSelector),a=s.parent(),c=!1):(a=$("<"+n.containerTag+" />"),""!==n.containerClass&&a.addClass(n.containerClass),a.prepend(n.label+" "),s=$('<input type="'+n.inputType+'" class="form-control" placeholder="'+n.placeholder+'" name="'+n.inputName+'" />'),n.preventReturnKey&&s.on("keydown",function(e){if(13===(e.keyCode||e.which))return e.preventDefault(),!1})),n.autofocus&&s.attr("autofocus",!0),$.fn.bindWithDelay?s.bindWithDelay("keyup",function(){r(e,$(this).val())},200):s.bind("keyup",function(){r(e,$(this).val())}),s.bind("click search input paste blur",function(){r(e,$(this).val())}),c&&a.append(s),n.quickList.length>0||n.quickListClear){if(l=n.quickListGroupTag?$("<"+n.quickListGroupTag+" />"):a,$.each(n.quickList,function(e,t){var r=$("<"+n.quickListTag+' class="'+n.quickListClass+'" />');r.text(i(t)),"A"===r[0].nodeName&&r.attr("href","#"),r.bind("click",function(e){e.preventDefault(),s.val(t).focus().trigger("click")}),l.append(r)}),n.quickListClear){var o=$("<"+n.quickListTag+' class="'+n.quickListClass+'" />');o.html(n.quickListClear),"A"===o[0].nodeName&&o.attr("href","#"),o.bind("click",function(e){e.preventDefault(),s.val("").focus().trigger("click")}),l.append(o)}l!==a&&a.append(l)}c&&e.before(a)}})}}(jQuery);

$(document).ready(function() {

	var gallery = $('#produtos a.img').simpleLightbox();

	$('#pedidos').DataTable({
		"ordering": false,
        "lengthMenu": [25, 50, 100],
        "language": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "searchPlaceholder": "Pesquisar...",
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

    $('#produtos').DataTable({
        "ordering": false,
        "bPaginate": false,
        "lengthMenu": [[100], [100]],
        "language": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "searchPlaceholder": "PESQUISAR PRODUTO...",
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

	$("#produtos").filterTable({
		placeholder: "PROCURAR PRODUTO...",
		containerClass: "form-group filter-table",
		callback: function(a, t) {
			t.find("tr").removeClass("striped").filter(":visible:even").addClass("striped")
		}
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

		var point = $(this).attr('data-point');

		if(point == 'site'){
			var returnURL = DOMAIN + '/pedidos';
		}else{
			var returnURL = DOMAIN + '/painel/pedidos';
		}

		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: DOMAIN + '/painel/pedidos/add/save',
			data: formData,
			type: 'POST',
			success: function(data){
				if (data == "1") {
					swal({type: 'success',title: 'SALVO COM SUCESSO!',showConfirmButton: false,timer: 1500});
					setTimeout(function() {window.location.href = returnURL;}, 1500);
				}else{
					$('#salvar').prop('type', 'submit');
					swal({type: 'error',title: 'ERRO AO SALVAR!',showConfirmButton: false,timer: 1500});
				}
			},
			processData: false,
			cache: false,
			contentType: false
		});
		
	});

	$('#editar_pedido').submit(function(e){
		var DOMAIN = $('body').data('domain');
		$('#salvar').prop('type', 'button');
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: DOMAIN + '/painel/pedidos/edit/save',
			data: formData,
			type: 'POST',
			success: function(data){
				if (data == "1") {
					swal({type: 'success',title: 'SALVO COM SUCESSO!',showConfirmButton: false,timer: 1500});
					setTimeout(function() {location.reload();}, 1500);
				}else{
					$('#salvar').prop('type', 'submit');
					swal({type: 'error',title: 'ERRO AO SALVAR!',showConfirmButton: false,timer: 1500});
				}
			},
			processData: false,
			cache: false,
			contentType: false
		});
	});

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
				if (data == "1") {
					swal({type: 'success',title: 'SALVO COM SUCESSO!',showConfirmButton: false,timer: 1500});
					setTimeout(function() {location.reload();}, 1500);
				}else{
					$('#salvar').prop('type', 'submit');
					swal({type: 'error',title: 'ERRO AO SALVAR!',showConfirmButton: false,timer: 1500});
				}
			},
			processData: false,
			cache: false,
			contentType: false
		});
	});
	
});

function statusPedido(id, status_pedido){
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
						setTimeout(function() {location.reload();}, 1500);
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
				setTimeout(function() {location.reload();}, 1500);
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
	$('#valor-itemTotal-'+item+'').val(numberToReal(itemTotal)); 

	var valorTotal = 0;

	$(".valorItemTotal").filter(function() {
		var totalbase = $(this).text();
		valorTotal += parseFloat(totalbase.replace('.','').replace(',','.'));
	});
	 
	$('.total').html(numberToReal(valorTotal));
	$('#valor_total_pedido').val(numberToReal(valorTotal));
	
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

$(document).ready(function() {
	$('#emitente_nome').on('keyup', function() {
		var DOMAIN = $('body').data('domain');
		var emitenteNome = $(this).val();
		$.ajax({
			type: 'POST',
			url: DOMAIN + '/painel/pedidos/emitente',
			data: {emitenteNome:emitenteNome},
			success: function (data) {
				var emitenteData = JSON.parse(data).emitente[0];
				$('#emitente_nome').val(emitenteData.nome);
				$('#emitente_cep').val(emitenteData.cep);
				$('#emitente_endereco').val(emitenteData.endereco);
				$('#emitente_bairrro').val(emitenteData.bairro);
				$('#emitente_cidade').val(emitenteData.cidade);
				$('#emitente_estado').val(emitenteData.uf);
			}
		});
	});
});