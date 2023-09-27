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


    $('form#importar_participantes').submit(function (e){
        var DOMAIN = $('body').data('domain');
        e.preventDefault();
        e.stopImmediatePropagation();
        $('.progress').show();
        var informacoes_progress = new FormData($(this)[0]);
        var file = $('input[type=file]')[0].files[0];
        informacoes_progress.append('informacoes_arquivo',file);
        $('#informacoes_progress').show();
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        $('.informacoes_progress').css('width',percentComplete+"%");
                        $('.informacoes_progress').html(percentComplete+"%");
                        console.log(percentComplete);
                        if (percentComplete === 100) {
                            $('.informacoes_progress').html(" SALVANDO AGUARDE... ");
                        }
                    }
                }, false);
                return xhr;
            },
            type:'POST',
            url:  DOMAIN + '/painel/palestras/participante/importar',
            data: informacoes_progress,
            async:true,
            cache:false,
            contentType: false,
            processData: false,
            success: function (returndata) {
                $('.informacoes_progress').removeClass('progress-bar-striped');
                $('.informacoes_progress').removeClass('progress-bar-animated');
                $('.informacoes_progress').addClass('bg-success');
                $('.informacoes_progress').html(" SALVO COM SUCESSO! ");
                $("#informacoes_arquivo").val("");
                $('#informacoes_uploadFile').hide();
            }
        });
        return false;
    });

});