$('#email').change(function() {
	let valor = $(this).val();
    validarCampo ('email', valor);
});

$('#telefone').change(function() {
	let valor = $(this).val();
    validarCampo ('telefone', valor);
});

$('#cpf').change(function() {
	let valor = $(this).val();
    validarCampo ('cpf', valor);
});

function validarCampo (campo, valor){
    let DOMAIN = $('body').data('domain');
    let id_palestra = $('#id_palestra').val();
    $.ajax({
        type: "POST",
        url: DOMAIN + '/palestras/inscricao/checkCadastroCampo',
        data: {'campo':campo, 'valor':valor, 'id_palestra':id_palestra},
        success: function(data) {
            if(data == 1){
                $('#'+campo).addClass('is-invalid');
                $('button[type="submit"]').prop("disabled", true);
            }else{
                $('#'+campo).removeClass('is-invalid');
                $('button[type="submit"]').prop("disabled", false);
            }
           
        }
    });
}