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

                    $.ajax({
                        type: "GET", 
                        async: true,
                        url: DOMAIN + '/visita/emailNovoEvento/'+data,
                        success: function () {}
                    });

                    $('.form-load').removeClass('show');
                    $('.cadastrar_visita').html('');
                    $('.cadastrar_visita_success').show();
                    $('.share_link_click').attr('data-link', DOMAIN + '/visita/inscricao/'+data);
                    $('.share_link_facebook').attr('href', 'https://www.facebook.com/sharer/sharer.php?u='+ DOMAIN +'/visita/inscricao/'+data);
                    $('.share_link_twitter').attr('href', 'https://twitter.com/intent/tweet?url='+ DOMAIN +'/visita/inscricao/'+data+'&amp;text=Confira%20esse%20agendamento%20em');
                    $('.share_link_whatsapp').attr('href', 'https://api.whatsapp.com/send/?text='+ DOMAIN +'/visita/inscricao/'+data);

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

        if(selected.val() == 'SP'){
            data_min = (60 / 100) * data_max;
        }else{
            data_min  = selected.attr('data-min');
        }
        console.log(data_min);
        if(data_max != undefined){
            $('.qty').show();
            $('#qtd_visitas').val(data_max);
            $('.minimo').text(Math.round(data_min));
            $('.maximo').text(data_max);
        }else{
            $('.qty').hide();
        }

        if(selected.val() == 'SP'){
            $('.digitar_qtd').show();
        }else{
            $('.digitar_qtd').hide();
        }

    });

    $('#qtd_visitas').on('keyup', function (e) {

        var data_max  = $(this).val();

        data_min = (60 / 100) * data_max;
        
        if(data_max != undefined){
            $('.qty').show();
            $('#qtd_visitas').val(data_max);
            $('.minimo').text(data_min.toFixed(0));
            $('.maximo').text(data_max);
        }else{
            $('.qty').hide();
        }

    });

});