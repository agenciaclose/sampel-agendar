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
  if (err) {
    console.log("Erro: " + err);
    return;
  }
  
  // Adiciona um ouvinte de clique ao botão de início
  startButton.addEventListener("click", function() {
    Quagga.start();
  });
  
  // Configura um ouvinte para quando um código de barras for lido
  Quagga.onDetected(function(result) {
    console.log(result);
    alert("Código de barras lido: " + result.codeResult.code);
    
    // Pare a leitura após um código de barras ser encontrado
    Quagga.stop();
  });
});