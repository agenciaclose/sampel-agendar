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

                    var visita_id = $('#visita_id').val();
                    window.location.href = DOMAIN + '/evento/visita/inscricao/'+visita_id+'?action=success';

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

function qrcodeGen(visita_id, user_cpf){
    $.ajax({
        type: "POST", 
        async: true, 
        data: {
                "frame_name": "bottom-frame",
                "qr_code_text": visita_id+'-'+user_cpf,
                "image_format": "SVG",
                "frame_color": "#02bfff",
                "frame_text_color": "#ffffff",
                "frame_icon_name": "mobile",
                "frame_text": "SAMPEL",
                "marker_left_template": "version13",
                "marker_right_template": "version13",
                "marker_bottom_template": "version13"
            },
        url: 'https://api.qr-code-generator.com/v1/create?access-token=pec_cfJ6r3zAxzXl-jCpj8hEj1_R9-9PlkdC8d_pf0Vjpls62BT9NxSQtnySGh43',
        success: function (data) {
            console.log(data);
        }
    });
}