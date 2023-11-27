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
    $.ajax({
        type: "POST",
        url: DOMAIN + '/visita/inscricao/checkCadastroCampo',
        data: {campo:campo, valor:valor},
        success: function(data) {
            if(data == 1){
                $('#'+campo).addClass('is-invalid');
            }else{
                $('#'+campo).removeClass('is-invalid');
            }
           
        }
    });
}