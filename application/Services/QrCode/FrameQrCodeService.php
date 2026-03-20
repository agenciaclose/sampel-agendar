<?php

namespace Agencia\Close\Services\QrCode;

use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\Common\{EccLevel, Version};
use chillerlan\QRCode\Output\QROutputInterface;

class FrameQrCodeService
{
    /**
     * Gera um SVG com "frame" (fundo/borda) e QR Code estilizado.
     *
     * A API aceita os mesmos campos usados hoje na chamada externa:
     * - qr_code_text
     * - frame_color, frame_text_color, frame_text, frame_icon_name
     * - marker_left_template, marker_right_template, marker_bottom_template
     */
    public function generateSvg(array $payload): string
    {
        $text = (string) ($payload['qr_code_text'] ?? '');
        if ($text === '') {
            throw new \InvalidArgumentException('qr_code_text não informado');
        }

        $frameBg = (string) ($payload['frame_color'] ?? '#246CB1');
        $qrFg = (string) ($payload['frame_text_color'] ?? '#ffffff');
        $frameText = (string) ($payload['frame_text'] ?? '');

        $markerLeft = (string) ($payload['marker_left_template'] ?? '');
        $markerRight = (string) ($payload['marker_right_template'] ?? '');
        $markerBottom = (string) ($payload['marker_bottom_template'] ?? '');

        $roundModules = ($markerLeft === 'version13' || $markerRight === 'version13' || $markerBottom === 'version13');

        // Mantém finder pattern e alguns elementos como quadrados quando módulos arredondados estiverem ativos.
        $keepAsSquare = $roundModules ? [
            QRMatrix::M_FINDER_DARK,
            QRMatrix::M_ALIGNMENT_DARK,
            QRMatrix::M_FORMAT_DARK,
            QRMatrix::M_VERSION_DARK,
            QRMatrix::M_TIMING_DARK,
            QRMatrix::M_SEPARATOR_DARK,
            QRMatrix::M_FINDER_DOT,
        ] : [];

        $options = new QROptions([
            'outputType' => QROutputInterface::MARKUP_SVG,
            'eccLevel' => EccLevel::M,
            'version' => Version::AUTO,
            'scale' => 6,
            'drawLightModules' => false,
            'bgColor' => $frameBg,
            'drawCircularModules' => $roundModules,
            'keepAsSquare' => $keepAsSquare,
            // Cor do "lado escuro" do QR (data + finder etc)
            'moduleValues' => [
                QRMatrix::M_DARKMODULE => $qrFg,
                QRMatrix::M_DATA_DARK => $qrFg,
                QRMatrix::M_FINDER_DARK => $qrFg,
                QRMatrix::M_SEPARATOR_DARK => $qrFg,
                QRMatrix::M_ALIGNMENT_DARK => $qrFg,
                QRMatrix::M_TIMING_DARK => $qrFg,
                QRMatrix::M_FORMAT_DARK => $qrFg,
                QRMatrix::M_VERSION_DARK => $qrFg,
                QRMatrix::M_LOGO_DARK => $qrFg,
                QRMatrix::M_QUIETZONE_DARK => $qrFg,
                QRMatrix::M_FINDER_DOT => $qrFg,
            ],
            // Evita "xml header" duplicado quando a gente monta o SVG final
            'svgAddXmlHeader' => false,
        ]);

        $qrSvg = (new QRCode($options))->render($text);

        $qrSize = $this->extractViewBoxSize($qrSvg);
        $qrInner = $this->extractSvgInner($qrSvg);

        // Layout do frame
        $pad = 12;
        $textArea = $frameText !== '' ? 42 : 0;

        $w = $qrSize + ($pad * 2);
        $h = $qrSize + ($pad * 2) + $textArea;

        $frameStroke = $qrFg; // borda igual à cor do QR

        $tx = ($w / 2);
        $ty = $qrSize + $pad + 30;

        $svg = [];
        $svg[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg[] = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %1$d %2$d" preserveAspectRatio="xMidYMid meet">',
            $w,
            $h
        );
        $svg[] = sprintf(
            '<rect x="0" y="0" width="%1$d" height="%2$d" rx="18" fill="%3$s"/>',
            $w,
            $h,
            htmlspecialchars($frameBg, ENT_QUOTES, 'UTF-8')
        );
        $svg[] = sprintf(
            '<rect x="0.5" y="0.5" width="%1$d" height="%2$d" rx="18" fill="none" stroke="%3$s" stroke-width="2"/>',
            $w,
            $h,
            htmlspecialchars($frameStroke, ENT_QUOTES, 'UTF-8')
        );

        // QR posicionado no frame
        $svg[] = sprintf('<g transform="translate(%1$d,%2$d)">%3$s</g>', $pad, $pad, $qrInner);

        // Texto (ex.: FEEDBACK, INSCRIÇÃO)
        if ($frameText !== '') {
            $svg[] = sprintf(
                '<text x="%1$d" y="%2$d" text-anchor="middle" font-family="Arial, sans-serif" font-size="22" font-weight="700" fill="%3$s">%4$s</text>',
                $tx,
                $ty,
                htmlspecialchars($qrFg, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($frameText, ENT_QUOTES, 'UTF-8')
            );
        }

        $svg[] = '</svg>';

        return implode('', $svg);
    }

    private function extractViewBoxSize(string $svg): int
    {
        if (preg_match('/viewBox="0 0 (\d+)\s+(\d+)"/', $svg, $m)) {
            return (int) $m[1];
        }

        // fallback: tenta pegar o primeiro número do viewBox
        if (preg_match('/viewBox="0 0\s+(\d+)/', $svg, $m)) {
            return (int) $m[1];
        }

        throw new \RuntimeException('Não foi possível identificar o viewBox do SVG do QR');
    }

    private function extractSvgInner(string $svg): string
    {
        // remove o header xml (se existir)
        $svg = preg_replace('/^\s*<\?xml[^?]*\?>\s*/', '', $svg);

        // remove tag <svg ...> e </svg>
        $svg = preg_replace('/^.*?<svg[^>]*>/s', '', $svg, 1);
        $svg = preg_replace('/<\/svg>\s*$/s', '', $svg, 1);

        return (string) $svg;
    }
}

