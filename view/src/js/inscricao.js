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

    $("#form_inscricao_editar").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/visita/inscricao/editar',
            success: function (data) {

                if (data == "success") {
                    swal({type: 'success', title: 'Editado com sucesso!', showConfirmButton: false, timer: 1500});
                    setTimeout(function() { location.reload(); }, 1500);
                } else {

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

$(".cpf_autocomplete").blur(function() {
    
    var DOMAIN = $('body').data('domain');
    var cpf = $(this).val();
    
    $.getJSON(DOMAIN + '/visita/inscricao/cpfautocomplete?cpf='+cpf, function(dados) {
        if (dados != null) {
            $("#empresa").val(dados.empresa);
            $("#nome").val(dados.nome);
            $("#email").val(dados.email);
            $("#cidade").val(dados.cidade);
            $("#estado").val(dados.estado);
            $("#cep").val(dados.cep);
            $("#telefone").val(dados.telefone);
            if (dados.setor !== "Auto Center" && dados.setor !== "Autopeças" && dados.setor !== "Balconista" && dados.setor !== "Distribuidora" && dados.setor !== "Estoquista" && dados.setor !== "Frotista" && dados.setor !== "Mecânico / Reparador" && dados.setor !== "Vendas / Telemarketing" && dados.setor !== "Outros") {
                $("#setor").val('Outros');               
                $(".outros").show();
                $(".outros input").val(dados.setor);
            }else{              
                $("#setor").val(dados.setor);               
            }

        }
    });

});

//Creating dynamic link that automatically click
$(document).ready(function() {
    var element = $("#inscricao_sucesso"); // global variable
    var getCanvas; // global variable
    var newData;

    $("#salvar_inscricao").on('click', function() {
        html2canvas(element,{
            onrendered: function(canvas) {
                getCanvas = canvas;
                var imgageData = getCanvas.toDataURL("image/png");
                var a = document.createElement("a");
                a.href = imgageData; //Image Base64 Goes here
                a.download = "inscricao.png"; //File name Here
                a.click(); //Downloaded file
            }
        });
    });
});

$(document).ready(function() {
    // Seleciona o SVG dentro da div com ID 'image_qrcode'
    var svg = $('#image_qrcode svg').get(0);

    if (svg) {
        // Serializa o SVG
        var serializer = new XMLSerializer();
        var svgStr = serializer.serializeToString(svg);

        // Cria um canvas temporário
        var canvas = document.createElement('canvas');
        canvas.width = 146;
        canvas.height = 190;
        var ctx = canvas.getContext('2d');

        // Cria um blob com o SVG
        var svgBlob = new Blob([svgStr], { type: 'image/svg+xml;charset=utf-8' });

        // Cria uma URL para o blob
        var url = URL.createObjectURL(svgBlob);

        // Carrega o SVG no canvas
        var img = new Image();
        img.onload = function() {
            // Desenha o SVG no canvas
            ctx.drawImage(img, 0, 0, 146, 190);

            // Converte o canvas em PNG
            var pngImg = new Image();
            pngImg.src = canvas.toDataURL('image/png');

            // Adiciona a imagem PNG ao body
            $('#image_qrcode_rend').append(pngImg);

            // Libera a URL do blob
            URL.revokeObjectURL(url);
        };

        img.src = url;
    } else {}
});

function editCPF() {
    $('.cpf-edit').hide();
    $('.cpf').show();
    $('.cpf').val('');
}