const config = {
  inputStream: {
      type: "LiveStream",
      target: document.querySelector("#camera"),
      constraints: {
          width: 400,
          height: 100,
          facingMode: "environment", // Pode ser "user" para câmera frontal
      },
  },
  locator: {
      patchSize: "medium",
      halfSample: true,
  },
  numOfWorkers: 2,
  decoder: {
      readers: ["code_128_reader"], // Tipo de código de barras que será lido (EAN no exemplo)
  },
  locate: true,
};

Quagga.init(config, function (err) {
  if (err) {
      console.error(err);
      return;
  }
  Quagga.start();
});

Quagga.onDetected(function (result) {
  const code = result.codeResult.code;
  document.querySelector("#resultado").textContent = "Código de Barras: " + code;
  Quagga.stop();
});