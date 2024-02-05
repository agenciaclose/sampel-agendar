$(document).ready(function () {
    // Enviar o formulário
    $('#sorteioForm').submit(function (event) {
        $('button[type="submit"]').prop("disabled", true);
        event.preventDefault();
        sendAjaxRequest();
    });
});

function sendAjaxRequest() {
    // Timer
    let countdown = 6;
    let timerInterval = setInterval(function () {
        countdown--;
        $('#timer').html(`O Sorteio será realizado em <div class="timer-number">${countdown}</div>`);
    
        if (countdown <= 0) {

            clearInterval(timerInterval);

            var DOMAIN = $('body').data('domain');
            const quantidade = $('#quantidade').val();
            const repescagem = $('#repescagem_input').val();
            const id_visita = $('#id_visita').val();

            $.ajax({
                type: "POST",
                url: DOMAIN + '/visita/sortear', // URL da API ou backend que irá processar os dados
                data: { quantidade: quantidade, repescagem: repescagem, id_visita: id_visita },
                success: function (response) {
                    window.location.href = DOMAIN + '/visita/sorteados/'+id_visita;
                },
                error: function (error) {
                    alert('Erro ao sortear');
                }
            });

        }

    }, 1000);
}
