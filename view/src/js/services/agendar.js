$(document).ready(function () {

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
                    $('.cadastrar_visita').html('');
                    $('.cadastrar_visita_success').show();
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

});