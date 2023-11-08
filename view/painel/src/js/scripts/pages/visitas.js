$(document).ready(function () {

    new FroalaEditor('#descricao', {
		key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
		enter: FroalaEditor.ENTER_BR,
		language: 'pt_br',
		pastePlain: true,
		attribution: false,
        theme: 'dark',
		toolbarButtons: {
			'moreText': {
			  'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'fontSize', 'clearFormatting'],
			  'buttonsVisible': 2
			},
			'moreParagraph': {
			  'buttons': ['alignLeft', 'alignCenter',  'alignRight']
			},
			'moreRich': {
			  'buttons': ['emoticons', 'fontAwesome']
			}
		}
	});


    $('.visita-excluir').click(function(){
		var domain = $('body').attr('data-domain');
		var visita = $(this).attr('data-visita');
		$.ajax({url: domain+'/painel/visita/excluir/'+visita, 
			success: function(result){
				location.reload();
			}
		});
	});
   
	$('.visita-status').click(function(){
		var domain = $('body').attr('data-domain');
		var visita = $(this).attr('data-visita');
		var action = $(this).attr('data-action');
		$.ajax({url: domain+'/painel/visita/status/'+action+'/'+visita, 
			success: function(result){
				location.reload();
			}
		});
	});

	$("#cadastrar_visita").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/agendar/cadastro',
            success: function (data) {

                if (data == "1") {
                    $('.form-load').removeClass('show');
                    swal({type: 'success', title: 'CADASTRADO COM SUCESSO!', showConfirmButton: false, timer: 1500});
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $("#editar_visita").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/agendar/editar',
            success: function (data) {

                if (data == "1") {
                    $('.form-load').removeClass('show');
                    swal({type: 'success', title: 'EDITADO COM SUCESSO!', showConfirmButton: false, timer: 1500});
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $('#qtd_visitas').on('keyup', function (e) {

        var data_min  = $('#qtd_visitas_min').val();
        var data_max  = $('#qtd_visitas').val();
        var porcentagem = $('#porcentagem').val();
        data_min = (porcentagem / 100) * data_max;
        
        if(data_max != undefined){
            $('.qty').show();
            $('#qtd_visitas_min').val(data_min.toFixed(0));
            $('#qtd_visitas').val(data_max);
            $('.minimo').text(data_min.toFixed(0));
            $('.maximo').text(data_max);
        }else{
            $('.qty').hide();
        }

    });

    $('#porcentagem').on('keyup', function (e) {
        console.log(porcentagem);
        var data_min  = $('#qtd_visitas_min').val();
        var data_max  = $('#qtd_visitas').val();
        var porcentagem = $('#porcentagem').val();
        data_min = (porcentagem / 100) * data_max;
        
        if(data_max != undefined){
            $('.qty').show();
            $('#qtd_visitas_min').val(data_min.toFixed(0));
            $('#qtd_visitas').val(data_max);
            $('.minimo').text(data_min.toFixed(0));
            $('.maximo').text(data_max);
        }else{
            $('.qty').hide();
        }

    });
});