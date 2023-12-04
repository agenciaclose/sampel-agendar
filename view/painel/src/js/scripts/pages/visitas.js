$(document).ready(function () {

    if ($('#descricao').length){
        new FroalaEditor('#descricao', {
            key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
            enter: FroalaEditor.ENTER_BR,
            language: 'pt_br',
            entities: '',
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
    }

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

        var data_max  = $('#qtd_visitas').val();
        var porcentagem = $('#porcentagem').val();
        data_min = (porcentagem / 100) * data_max;
        
        if(data_max != undefined){
            $('.qty').show();
            $('#qtd_visitas').val(data_max);
            $('.minimo').text(data_min.toFixed(0));
            $('.maximo').text(data_max);
        }else{
            $('.qty').hide();
        }

    });

    $('#porcentagem').on('keyup', function (e) {
        console.log(porcentagem);
        var data_max  = $('#qtd_visitas').val();
        var porcentagem = $('#porcentagem').val();
        data_min = (porcentagem / 100) * data_max;
        
        if(data_max != undefined){
            $('.qty').show();
            $('#qtd_visitas').val(data_max);
            $('.minimo').text(data_min.toFixed(0));
            $('.maximo').text(data_max);
        }else{
            $('.qty').hide();
        }

    });

    $(".send_email_certificado").click(function (c) {

        $(this).prop("disabled", true);
        $(this).html('<i class="fa-solid fa-sync fa-spin"></i> EMAILS SENDO ENVIADOS');
    
        c.preventDefault();
        let DOMAIN = $('body').data('domain');
        let visita_id = $(this).data('visita');
    
        $.ajax({
            type: "GET", 
            async: true,
            url: DOMAIN + '/visita/sendEmailCertificado/'+visita_id,
            success: function () {
                swal({type: 'success', title: 'Emails enviados com sucesso', showConfirmButton: false, timer: 1500});
                setTimeout(function() { location.reload(); }, 1500);
            }
        });
    });

});

$('#setor').change(function() {
	if($(this).val() == 'Outros'){
		$('.outros').show();
	}else{
		$('.outros').hide();
	}
});

// jQuery Mask 
var maskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
options = {
    onKeyPress: function(val, e, field, options) {
        field.mask(maskBehavior.apply({}, arguments), options);
    }
};

$('.telefone').mask(maskBehavior, options);
$('.cep').mask('00000-000', {reverse: true, clearIfNotMatch: true});
$('.cpf').mask('000.000.000-00', {reverse: true, clearIfNotMatch: true});
$('.cnpj').mask('00.000.000/0000-00', {reverse: true});

$('.cpf_number').mask('00000000000', {reverse: true, clearIfNotMatch: true});