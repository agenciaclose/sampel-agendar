$(document).ready(function () {

    $('.tipo').change(function(){
		var tipo = $(this).val();
        if(tipo == 'Opcoes'){
            $('.tipo_input').show();
        }else{
            $('.tipo_input').hide();
        }
	});

	$("#form_add_pergunta").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/feedback/perguntas/save',
            success: function (data) {

                if (data == "1") {
                    $('.form-load').removeClass('show');
                    swal({type: 'success', title: 'CADASTRADO COM SUCESSO!', showConfirmButton: false, timer: 1500});
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


    $('.pergunta-excluir').click(function(){
        var domain = $('body').attr('data-domain');
        var pergunta = $(this).attr('data-pergunta');
        Swal.fire({
            title: 'Deseja realmente excluir?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Excluir'
        }).then((result) => {
            if (result.value == true) {
                $.ajax({url: domain+'/painel/feedback/perguntas/excluir/'+pergunta, 
                    success: function(result){
                        location.reload();
                    }
                });
            }
        })
	});

    
});