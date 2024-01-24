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
                if(campo == 'cpf'){
                    var data = new Date(data);
                    var dia = data.getDate().toString().padStart(2, '0');
                    var mes = (data.getMonth() + 1).toString().padStart(2, '0');
                    var ano = data.getFullYear();
                    var dataFormatada = dia + "/" + mes + "/" + ano;
                    $('#uservisita').text(dataFormatada);
                    $('#cpf_notification').modal('show');
                }
            }
           
        }
    });
}