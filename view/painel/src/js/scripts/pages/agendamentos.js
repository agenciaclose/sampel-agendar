$('#add_link_agentamento').submit(function(e){
    $(this).children(':input[value=""]').attr("disabled", "disabled");
    var domain = $('body').data('domain');
    $('#salvar').prop('type', 'button');
    $('#salvar').addClass('disabled');
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: domain + '/painel/servicos/agendamentos/link',
        data: formData,
        type: 'post',
        success: function(data){
            if (data == "1") {
                swal({type: 'success',title: 'SALVO COM SUCESSO!',showConfirmButton: false,timer: 2000});
                setTimeout(function() {location.reload();}, 2000);
            }else{
                $('#salvar').prop('type', 'submit');
                $('#salvar').removeClass('disabled');
                swal({type: 'error',title: 'ERRO AO SALVAR!',showConfirmButton: false,timer: 2000});
            }
        },
        processData: false,
        cache: false,
        contentType: false
    });
});