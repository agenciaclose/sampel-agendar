// Selecione o elemento da câmera e o botão de início
var cameraElement = document.getElementById("camera");
var startButton = document.getElementById("startButton");

var config = {
  inputStream: {
    name: "Live",
    type: "LiveStream",
    target: cameraElement,
    constraints: {
      width: 400,
      height: 100,
      facingMode: "environment"
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
  
  Quagga.onProcessed(function (result) {
    var drawingCtx = Quagga.canvas.ctx.overlay,
        drawingCanvas = Quagga.canvas.dom.overlay;

    if (result) {
        if (result.boxes) {
            drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
            result.boxes.filter(function (box) {
                return box !== result.box;
            }).forEach(function (box) {
                Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "blue", lineWidth: 2 });
            });
        }

        if (result.box) {
            Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "blue", lineWidth: 2 });
        }

        if (result.codeResult && result.codeResult.code) {
            Quagga.ImageDebug.drawPath(result.line, { x: 'vertical', y: 'horizontal' }, drawingCtx, { color: 'red', lineWidth: 3 });
        }
    }
});

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