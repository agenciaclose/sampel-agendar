$(document).ready(function () {

    $("#form_inscricao").submit(function (c) {

        $('.alert-2').hide();
        $('.alert-3').hide();
        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/visita/inscricao/cadastro',
            success: function (last) {

                if (last != "0") {

                    var id_visita = $('#id_visita').val();
                    var user_email = $('#user_email').val();
                    var cpf = $('#cpf').val().replace('.', '').replace('-', '');
                    
                    qrcodeGen(id_visita, user_email, cpf, last);

                } else {

                    $('.alert-2').show();
                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

});

function qrcodeGen(id_visita, user_email, cpf, last) {
    var DOMAIN = $('body').data('domain');
    
    $.ajax({
        type: "POST", 
        async: true, 
        data: { 
                "frame_name": "bottom-frame",
                "qr_code_text": DOMAIN + '/visita/feedback/'+cpf+'/'+id_visita,
                "image_format": "SVG",
                "frame_color": "#246CB1",
                "frame_text_color": "#ffffff",
                "frame_icon_name": "mobile",
                "frame_text": "SAMPEL",
                "marker_left_template": "version13",
                "marker_right_template": "version13",
                "marker_bottom_template": "version13"
            },
        url: 'https://api.qr-code-generator.com/v1/create?access-token=pec_cfJ6r3zAxzXl-jCpj8hEj1_R9-9PlkdC8d_pf0Vjpls62BT9NxSQtnySGh43',
        success: function (qrcode) {
            qrcode = (new XMLSerializer()).serializeToString(qrcode);
            qrcodeSave(id_visita, cpf, qrcode, last);
        }
    });
}

function qrcodeSave (id_visita, cpf, qrcode, last){
	var DOMAIN = $('body').data('domain');

    const formData = new FormData()
    formData.append('id_visita', id_visita);
    formData.append('cpf', cpf);
    formData.append('qrcode', qrcode);

    $.ajax({
        type: "POST",
        url: DOMAIN + '/visita/inscricao/cadastro-qrcode',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            window.location.href = DOMAIN + '/visita/inscricao/'+id_visita+'/'+last+'?action=success';
        }
    });
}

var printDiv = function (divName){
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}
$('#setor').change(function() {
	if($(this).val() == 'Outros'){
		$('.outros').show();
	}else{
		$('.outros').hide();
	}
});

function limpa_formulário_cep() {
    $("#cidade").val('');
    $("#estado").val('');
    $('.endereco').html('');
}

$("#cep").blur(function() {
    var cep = $(this).val().replace(/\D/g, '');
    if (cep != '') {
        var validacep = /^[0-9]{8}$/;
        if(validacep.test(cep)) {
            $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                if (!("erro" in dados)) {
                    $("#cidade").val(dados.localidade);
                    $("#estado").val(dados.uf);
                    $('.endereco').html(dados.localidade+', '+dados.uf);
                }
                else {
                    limpa_formulário_cep();
                    alert("CEP não encontrado.");
                }
            });
        }
        else {
            limpa_formulário_cep();
            alert("Formato de CEP inválido.");
        }
    }
    else {
        limpa_formulário_cep();
    }
});

$('#email').change(function() {
	let valor = $(this).val();
    validarCampo ('email', valor);
});

$('#telefone').change(function() {
	let valor = $(this).val();
    validarCampo ('telefone', valor);
});

$('#cpf').change(function() {
	let valor = $(this).val();
    validarCampo ('cpf', valor);
});

function validarCampo (campo, valor){
    
    let DOMAIN = $('body').data('domain');
    $.ajax({
        type: "POST",
        url: DOMAIN + '/visita/inscricao/checkCadastroCampo',
        data: {campo:campo, valor:valor},
        success: function(data) {
            if(data == 1){
                $('#'+campo).addClass('is-invalid');
            }else{
                $('#'+campo).removeClass('is-invalid');
            }
           
        }
    });

}