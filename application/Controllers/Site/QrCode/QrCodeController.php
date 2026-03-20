<?php

namespace Agencia\Close\Controllers\Site\QrCode;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Services\QrCode\FrameQrCodeService;

class QrCodeController extends Controller
{
    public function generate($params)
    {
        $this->setParams($params);

        try {
            $payload = $_POST;
            $service = new FrameQrCodeService();
            $svg = $service->generateSvg($payload);

            // Mantém o mesmo comportamento esperado pelo frontend (XMLSerializer no response).
            // Importante: não use image/svg+xml pois o jQuery pode parsear como XMLDocument.
            // Queremos a string SVG crua no JS para salvar corretamente no banco.
            header('Content-Type: text/plain; charset=utf-8');
            echo $svg;
        } catch (\Throwable $e) {
            http_response_code(400);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'error: ' . $e->getMessage();
        }
    }
}

