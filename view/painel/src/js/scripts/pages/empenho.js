$(document).ready(function () {

    var options = {
        onKeyPress: function(cep, e, field, options){
            if (cep.length<=6)
            {
                var inputVal = parseFloat(cep);
                jQuery('.money').val(inputVal.toFixed(2));
            }                        
            var masks = ['#.##0,00', '0,00'];
            mask = (cep == 0) ? masks[1] : masks[0];
            $('.money').mask(mask, options);
        },
        reverse: true
    };
    $('.money').mask('#.##0,00', options);

	$("#add_empenho").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/empenho/add/save',
            success: function (data) {
                if (data != "0") {
                    swal({type: 'success', title: 'Salvo com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $("#edit_empenho").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/empenho/edit/save',
            success: function (data) {
                if (data == "1") {
                    swal({type: 'success', title: 'Editado com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

});

function removeEmpenho(id) {
	Swal.fire({
		title: "Remover esse Empenho?",
		text: "Ele será removido dos relatórios.",
		showCancelButton: true,
		cancelButtonText: 'Não',
		confirmButtonText: 'Sim',
		dangerMode: true,
	}).then((result) => {
		if (result.value === true) {
			var DOMAIN = $('body').data('domain');
            $.ajax({
                type: "POST", 
                async: true,
                data: {'id': id},
                url: DOMAIN + '/painel/empenho/remove',
                success: function (data) {
                    if (data == "success") {
                        window.location.reload();
                    }else{
                        alert('ERRO AO ATUALIZADO');
                    }
                }
            });
		}
	});
}