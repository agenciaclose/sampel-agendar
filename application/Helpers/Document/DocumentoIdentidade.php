<?php

namespace Agencia\Close\Helpers\Document;

/**
 * Limpa e formata CPF (11 dígitos) ou RG (outros tamanhos).
 * Persistência: apenas dígitos. Formatação: só no backend (exibição/exportação).
 */
class DocumentoIdentidade
{
    public static function onlyDigits($value): string
    {
        return preg_replace('/\D+/', '', (string) $value) ?? '';
    }

    public static function isCpf($value): bool
    {
        return strlen(self::onlyDigits($value)) === 11;
    }

    /**
     * Formata para exibição: CPF 000.000.000-00 ou RG com pontos/traço.
     */
    public static function format($value): string
    {
        $digits = self::onlyDigits($value);
        if ($digits === '') {
            return '';
        }

        if (strlen($digits) === 11) {
            return substr($digits, 0, 3) . '.' .
                substr($digits, 3, 3) . '.' .
                substr($digits, 6, 3) . '-' .
                substr($digits, 9, 2);
        }

        // RG: corpo com pontos a cada 3 dígitos (da direita) + dígito verificador
        if (strlen($digits) >= 8) {
            $body = substr($digits, 0, -1);
            $dv = substr($digits, -1);
            $bodyFmt = preg_replace('/\B(?=(\d{3})+(?!\d))/', '.', $body);
            return $bodyFmt . '-' . $dv;
        }

        return $digits;
    }

    /**
     * Formatação com máscara de privacidade (ex.: ingresso).
     */
    public static function formatMasked($value): string
    {
        $digits = self::onlyDigits($value);
        if ($digits === '') {
            return '';
        }

        if (strlen($digits) === 11) {
            return substr($digits, 0, 3) . '.***.***-' . substr($digits, 9, 2);
        }

        if (strlen($digits) >= 4) {
            $start = substr($digits, 0, 2);
            $end = substr($digits, -1);
            return $start . str_repeat('*', max(strlen($digits) - 3, 1)) . '-' . $end;
        }

        return $digits;
    }
}
