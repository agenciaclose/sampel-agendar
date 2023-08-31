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
            success: function (data) {

                if (data == "1") {

                    var id_visita = $('#id_visita').val();
                    var user_email = $('#user_email').val();
                    var id_user = $('#id_user').val();
                    
                    qrcodeGen(id_visita, user_email, id_user);

                } else if (data == "2") {

                    $('.alert-2').show();
                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }else{

                    $('.alert-3').show();
                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

});

function qrcodeGen(id_visita, user_email, id_user) {
	var DOMAIN = $('body').data('domain');
    $.ajax({
        type: "POST", 
        async: true, 
        data: {
                "frame_name": "bottom-frame",
                "qr_code_text": DOMAIN + '/visita/feedback/'+id_user+'/'+id_visita,
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
            qrcodeSave(id_visita, id_user, qrcode);
        }
    });
}

function qrcodeSave (id_visita, id_user, qrcode){
    const formData = new FormData()
    formData.append('id_visita', id_visita);
    formData.append('id_user', id_user);
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
            window.location.href = DOMAIN + '/visita/inscricao/'+id_visita+'?action=success';
        }
    });
}