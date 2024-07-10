$(document).ready(function () {

	$("#add_cargo").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/equipes/cargos/add/save',
            success: function (data) {

                if (data == "1") {
                    swal({type: 'success', title: 'Salvo com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $("#edit_cargo").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/equipes/cargos/edit/save',
            success: function (data) {
                if (data == "1") {
                    swal({type: 'success', title: 'Editado com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $(".removerole").click(function (c) {

        var DOMAIN = $('body').data('domain');
        var id = $(this).data('item');

        Swal.fire({
            title: "Atenção",
            text: "Tem certeza que deseja deletar?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, deletar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.value) {
                console.log(result);
              $.ajax({
                  type: "POST", async: true, data: {'id': id},
                  url: DOMAIN + '/painel/equipes/cargos/remove',
                  success: function () {
                      swal({type: 'success', title: 'Removido com sucesso', showConfirmButton: false, timer: 2000});
                      setTimeout(function(){
                          window.location.reload();
                      }, 2000);                
                  }
              });
            }
        });

    });

});