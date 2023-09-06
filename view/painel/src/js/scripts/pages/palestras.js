$(document).ready(function () {

	$("#cadastrar_palestra").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/palestras/cadastro',
            success: function (data) {

                if (data == "1") {
                    $('.form-load').removeClass('show');
                    $('.cadastrar_palestra').html('');
                    $('.cadastrar_palestra_success').show();
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $("#editar_palestra").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/palestras/editar',
            success: function (data) {

                if (data == "1") {
                    $('.form-load').removeClass('show');
                    $('.editar_palestra').html('');
                    $('.editar_palestra_success').show();
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });


    $("#cadastro_participante").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/palestras/participante/cadastro',
            success: function (data) {

                if (data == "1") {
                    location.reload();
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $("#editar_participante").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/palestras/participante/editar',
            success: function (data) {

                if (data == "1") {

                    location.reload();

                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $(".excluir_participante").click(function (c) {
        c.preventDefault();
        var id = $(this).attr('data-id');
        var DOMAIN = $('body').data('domain');
        $.ajax({
            type: "POST",
            data: {id: id},
            url: DOMAIN + '/painel/palestras/participante/excluir',
            success: function (data) {

                if (data == "1") {
                    location.reload();
                }
            }
        });

    });

});