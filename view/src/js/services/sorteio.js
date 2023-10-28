$(document).ready(function () {
    // Enviar o formulário
    $('#sorteioForm').submit(function (event) {
        event.preventDefault();
        sendAjaxRequest();
    });
});

function sendAjaxRequest() {
    // Timer
    let countdown = 6;
    let timerInterval = setInterval(function () {
        countdown--;
        $('#timer').text(`O Sorteio será realizado em <div class="">${countdown}</div>`);
    
        if (countdown <= 0) {
            clearInterval(timerInterval);
            const quantidade = $('#quantidade').val();
        
            $.ajax({
                type: "POST",
                url: "/api/sorteio", // URL da API ou backend que irá processar os dados
                data: { quantidade: quantidade },
                success: function (response) {
                    alert('Dados enviados com sucesso!');
                    $('#myModal').modal('hide');
                },
                error: function (error) {
                    alert('Erro ao enviar os dados!');
                }
            });
        }

    }, 1000);
}
