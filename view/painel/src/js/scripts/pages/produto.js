$(document).ready(function () {

    var gallery = $('.produtos a.img').simpleLightbox();

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    $('input[name="quantidade"], input[name="unidades"]').on('keyup', function () {
        var quantidade = $('input[name="quantidade"]').val();
        var unidades = $('input[name="unidades"]').val();
        $('.estoque').val(quantidade * unidades);        
    });

    $("#add_produto").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this)[0];
        var formData = new FormData(form);

        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            data: formData,
            url: DOMAIN + '/painel/produtos/add/save',
            success: function (data) {

                if (data == "1") {
                    swal({type: 'success', title: 'Salvo com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                       window.location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $("#edit_produto").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this)[0];
        var formData = new FormData(form);

        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            data: formData,
            url: DOMAIN + '/painel/produtos/edit/save',
            success: function (data) {
                if (data == "1") {
                    swal({type: 'success', title: 'Editado com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

});

function statusProduct(id, status){
    var DOMAIN = $('body').data('domain');
    $.ajax({
        type: "POST", 
        async: true,
        data: {'id': id, status: status},
        url: DOMAIN + '/painel/produtos/status',
        success: function (data) {
            if (data == "1") {
                window.location.reload();
            }else{
                alert('ERRO AO ATUALIZADO');
            }
        }
    });
}

$('.money').mask("#.##0,00", {reverse: true});