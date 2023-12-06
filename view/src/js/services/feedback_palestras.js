$(document).ready(function () {

	$("#check_inscricao").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/palestras/feedback/checkInscricao',
            success: function (data) {
                console.log(data);
                // if (data == "1") {

                //     $('.form-load').removeClass('show');
                //     $('#save_feedback').html('');
                //     $('.success-feedback').show();

                // } else {

                //     $('button[type="submit"]').prop("disabled", false);
                //     $('.form-load').removeClass('show');

                // }
            }
        });

    });

    $("#save_feedback").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/palestras/feedback/save',
            success: function (data) {

                if (data == "1") {

                    $('.form-load').removeClass('show');
                    $('#save_feedback').html('');
                    $('.success-feedback').show();

                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

});