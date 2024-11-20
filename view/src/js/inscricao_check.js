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
    inscricaoAutoComplete ('cpf', valor);
});

function validarCampo (campo, valor){
    let DOMAIN = $('body').data('domain');
    let id_visita = $('#id_visita').val();
    let tipo_visita = $('#tipo_visita').val();
    $.ajax({
        type: "POST",
        url: DOMAIN + '/visita/inscricao/checkCadastroCampo',
        data: {'campo':campo, 'valor':valor, 'id_visita':id_visita, 'tipo_visita':tipo_visita},
        success: function(data) {
            if(data == 0){
                $('#'+campo).removeClass('is-invalid');
                $('button[type="submit"]').prop("disabled", false);
            }else{
                $('#'+campo).addClass('is-invalid');
                $('button[type="submit"]').prop("disabled", true);
           
                var data = new Date(data);
                var dia = data.getDate().toString().padStart(2, '0');
                var mes = (data.getMonth() + 1).toString().padStart(2, '0');
                var ano = data.getFullYear();
                var dataFormatada = dia + "/" + mes + "/" + ano;
                $('#uservisita').text(dataFormatada);
                $('#cpf_notification').modal('show');
                
            }
           
        }
    });
}

function inscricaoAutoComplete(campo, cpf) {
    let DOMAIN = $('body').data('domain');
    
    $.ajax({
        type: "POST",
        url: DOMAIN + '/visita/inscricao/inscricaoAutocomplete',
        data: { 'cpf': cpf },
        dataType: 'json',
        success: function(data) {
            if (data != '') {
                // Preencher os campos do formulário
                $("#empresa").val(data.empresa);
                $("#nome").val(data.nome);
                $("#email").val(data.email);
                $("#cidade").val(data.cidade);
                $("#estado").val(data.estado);
                $("#cep").val(data.cep);
                $("#telefone").val(data.telefone);
                $("#setor").val(data.setor);
            } else {
                // Limpar e habilitar os campos
                $('#nome').val('');
                $('#empresa').val('');
                $('#email').val('');
                $('#setor').val('');
                $('#cep').val('');
                $('#cidade').val('');
                $('#estado').val('');
                $('#telefone').val('');
            }
        }
    });
}