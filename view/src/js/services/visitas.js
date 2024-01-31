$("#form_editar_equipe").submit(function (e) {

    e.preventDefault();

    $('.form-load').addClass('show');
    $('button[type="submit"]').prop("disabled", true);

    var DOMAIN = $('body').data('domain');
    var form = $(this);

    $.ajax({
        type: "POST", async: true, data: form.serialize(),
        url: DOMAIN + '/visita/listaEquipesSave',
        success: function (data) {

            if (data == "0") {
                $('.form-load').removeClass('show');
                swal({type: 'success', title: 'Equipe editada com sucesso!', showConfirmButton: false, timer: 1500});
                setTimeout(function() { location.reload(); }, 1500);
            } else {

                $('button[type="submit"]').prop("disabled", false);
                $('.form-load').removeClass('show');

            }
        }
    });

});

$('.remover_equipe').click(function (e) {

    e.preventDefault();
    var DOMAIN = $('body').data('domain');
    let visita = $(this).data("visita");
    let membro = $(this).data("membro");

    $.ajax({
        type: "POST", 
        async: true, 
        data: { 'visita': visita, 'membro': membro },
        url: DOMAIN + '/visita/removeEquipe',
        success: function (data) {

            if (data == "0") {

                $('.form-load').removeClass('show');
                swal({type: 'success', title: 'Removido com sucesso!', showConfirmButton: false, timer: 1500});
                $('.lista_equipe .'+visita+'-'+membro).hide();
                //setTimeout(function() { location.reload(); }, 1500);

            } else {
                swal({type: 'warning', title: 'Erro ao remover!', showConfirmButton: false, timer: 1500});
                $('button[type="submit"]').prop("disabled", false);
                $('.form-load').removeClass('show');

            }
        }
    });

});


// GERA QRCODE DA PALESTRA
function qrcodeGenVisitas(id_visita) {
    var DOMAIN = $('body').data('domain');
    
    $.ajax({
        type: "POST", 
        async: true, 
        data: { 
                "frame_name": "bottom-frame",
                "qr_code_text": DOMAIN + '/visita/inscricao/'+id_visita,
                "image_format": "SVG",
                "frame_color": "#246CB1",
                "frame_text_color": "#ffffff",
                "frame_icon_name": "mobile",
                "frame_text": "INSCRIÇÃO",
                "marker_left_template": "version13",
                "marker_right_template": "version13",
                "marker_bottom_template": "version13"
            },
        url: 'https://api.qr-code-generator.com/v1/create?access-token=pec_cfJ6r3zAxzXl-jCpj8hEj1_R9-9PlkdC8d_pf0Vjpls62BT9NxSQtnySGh43',
        success: function (qrcode) {
            qrcode = (new XMLSerializer()).serializeToString(qrcode);
            qrcodeSaveVisitas(id_visita, qrcode);
        }
    });
}

function qrcodeSaveVisitas (id_visita, qrcode){
    var DOMAIN = $('body').data('domain');

    const formData = new FormData()
    formData.append('id_visita', id_visita);
    formData.append('qrcode', qrcode);

    $.ajax({
        type: "POST",
        url: DOMAIN + '/visita/cadastro/save-qrcode',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            qrcodeGenFeedback(id_visita);
        }
    });
}
// ##

// GERA QRCODE DO FEEDBACK
function qrcodeGenFeedback(id_visita) {
    var DOMAIN = $('body').data('domain');
    
    $.ajax({
        type: "POST", 
        async: true, 
        data: { 
                "frame_name": "bottom-frame",
                "qr_code_text": DOMAIN + '/visita/feedback/'+id_visita,
                "image_format": "SVG",
                "frame_color": "#246CB1",
                "frame_text_color": "#ffffff",
                "frame_icon_name": "mobile",
                "frame_text": "FEEDBACK",
                "marker_left_template": "version13",
                "marker_right_template": "version13",
                "marker_bottom_template": "version13"
            },
        url: 'https://api.qr-code-generator.com/v1/create?access-token=pec_cfJ6r3zAxzXl-jCpj8hEj1_R9-9PlkdC8d_pf0Vjpls62BT9NxSQtnySGh43',
        success: function (qrcode) {
            qrcode = (new XMLSerializer()).serializeToString(qrcode);
            qrcodeSaveFeedback(id_visita, qrcode);
        }
    });
}

function qrcodeSaveFeedback (id_visita, qrcode){
    var DOMAIN = $('body').data('domain');

    const formData = new FormData()
    formData.append('id_visita', id_visita);
    formData.append('qrcode', qrcode);

    $.ajax({
        type: "POST",
        url: DOMAIN + '/visita/cadastro/save-qrcode-feedback',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            window.location.href = DOMAIN + '/visita/qrcode/'+id_visita;
        }
    });
}
// ##
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


    $("#editar_visita_form").submit(function (c) {
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
});