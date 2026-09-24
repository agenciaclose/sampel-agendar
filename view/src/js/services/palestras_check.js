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
    // Sem restrição de CPF/e-mail/telefone duplicados na inscrição de palestras
    $('#'+campo).removeClass('is-invalid');
    $('button[type="submit"]').prop("disabled", false);
}
