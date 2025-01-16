$(function () {
    $('form#importar_palestras').submit(function (e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var informacoes_progress = new FormData($(this)[0]);
        var file = $('input[type=file]')[0].files[0];
        informacoes_progress.append('informacoes_arquivo',file);
        var DOMAIN = $('body').data('domain');

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
            url: DOMAIN + '/painel/importar/salvar',
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
