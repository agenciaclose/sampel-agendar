<div class="p-2">
    <form action="javascript:void(0)" id="add_link_agentamento" method="POST">
        <div class="fa-lg mb-2"><b>INFORMATIVO:</b></div>
        <div class="mb-2">Informe o link da consultoria para seu cliente. Será enviado imediatamente para seu cliente e será enviado novamente para ele no dia do agendamento.</div>
        <div class="mb-2">
            <input type="text" class="form-control" name="link" placeholder="Link do Agendamento">
        </div>
        <button class="btn btn-primary btn-block w-100" type="submit" id="salvar">SALVAR INFORMAÇÕES</button>
        <input type="hidden" name="agendamento" value="<?php echo $_GET['id']; ?>">
    </form>
</div>

<script>
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
</script>