// Selecione o elemento da câmera e o botão de início
var cameraElement = document.getElementById("camera");
var startButton = document.getElementById("startButton");

var config = {
  inputStream: {
    name: "Live",
    type: "LiveStream",
    target: cameraElement,
    constraints: {
      width: { min: 400 },
      height: { min: 100 },
      facingMode: "environment"
    },
    area: {
      top: "0%",
      right: "0%",
      left: "0%",
      bottom: "0%"
    },
    singleChannel: false
  },
  decoder: {
    readers: ["code_128_reader"]
  },
};

// Inicializa o leitor de código de barras
Quagga.init(config, function(err) {
  Quagga.start();

  if (err) {
    console.log("Erro: " + err);
    return;
  }
  
  // // Adiciona um ouvinte de clique ao botão de início
  // startButton.addEventListener("click", function() {
  //   $('#sendContent').html('<button type="button" class="btn btn-warning btn-lg w-100 fw-bold rounded-0"><i class="fa-solid fa-sync fa-spin"></i> VERIFICANDO...</button>');
  //   Quagga.start();
  // });
  
  // Configura um ouvinte para quando um código de barras for lido
  Quagga.onDetected(function(result) {

    $('#sendContent').html('<button type="button" class="btn btn-warning btn-lg w-100 fw-bold rounded-0"><i class="fa-solid fa-sync fa-spin"></i> VERIFICANDO...</button>');
    
    let id_visita = $('#id_visita').val();
    let DOMAIN = $('body').data('domain');

    if(result.codeResult.code) {

      $.ajax({
        type: "POST", 
        async: true, 
        data: { 'id_visita': id_visita, 'codigo': result.codeResult.code},
        url: DOMAIN + '/visita/recepcao/confirmar',
        success: function (data) {
          if(data == '0'){
            $('#sendContent').html('<button type="button" class="btn btn-success btn-lg w-100 fw-bold rounded-0"><i class="fa-solid fa-shield-check fa-beat"></i> VERIFICADO</button>');
            setTimeout(function() { location.reload(); }, 1500);
          }else{
            $('#sendContent').html('<button type="button" class="btn btn-danger btn-lg w-100 fw-bold rounded-0"><i class="fa-solid fa-triangle-exclamation fa-fade"></i> ERRO AO VERIFICAR</button>');
            setTimeout(function() { location.reload(); }, 3000);
          }
        }
      });

    }else{
      alert('ERRO AO LER O CÓDIGO')
    }
    
    // Pare a leitura após um código de barras ser encontrado
    Quagga.stop();
  });
});