Quagga.init({
  inputStream: {
    name: "Live",
    type: "LiveStream",
    target: document.querySelector('#camera'),
    constraints: {
      width: 400,
      height: 100,
      facingMode: "environment"
    },
  },
  decoder: {
    readers: ["code_128_reader"] // Tipo de código de barras a ser lido (EAN neste exemplo)
  },
  numOfWorkers: 4, // Número de trabalhadores a serem usados para a decodificação
  locate: true, // Localização da região do código de barras
});

Quagga.onProcessed(function(result) {
  var drawingCtx = Quagga.canvas.ctx.overlay;
  var drawingCanvas = Quagga.canvas.dom.overlay;

  if (result) {
    if (result.boxes) {
      drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
      result.boxes.forEach(function(box) {
        Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
      });
    }

    if (result.box) {
      Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "blue", lineWidth: 2 });
    }

    if (result.codeResult && result.codeResult.code) {
      console.log("Código de barras detectado: " + result.codeResult.code);
    }
  }
});

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

});

Quagga.start();

// Para parar a detecção de código de barras, você pode usar:
// Quagga.stop();