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

                if (data != "0") {
                    $('#share_link').attr('data-link', DOMAIN + '/visita/inscricao/'+data);
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


    $('#estado').on('change', function (e) {

        var selected = $(this).find("option:selected");
        var data_min  = selected.attr('data-min');
        var data_max  = selected.attr('data-max');

        if(data_max != undefined){
            $('.qty').show();
            $('#qtd_visitas').val(data_max);
            $('.minimo').text(data_min);
            $('.maximo').text(data_max);
        }else{
            $('.qty').hide();
        }

    });

});