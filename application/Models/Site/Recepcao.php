<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Helpers\Document\DocumentoIdentidade;
use Agencia\Close\Models\Model;

class Recepcao extends Model
{
    /**
     * Monta a cláusula de busca por código, e-mail e documento (CPF/RG só dígitos).
     */
    private function clausulaBuscaInscricao(string $busca, string $digitos, bool $incluirEmail = false): array
    {
        $conds = ["`codigo` = :busca", "`cpf` = :busca"];
        if ($incluirEmail) {
            $conds[] = "`email` = :busca";
        }
        $parse = "busca={$busca}";

        if ($digitos !== '' && $digitos !== $busca) {
            $conds[] = "`cpf` = :digitos";
            $parse .= "&digitos={$digitos}";
        } elseif ($digitos !== '') {
            // busca já é só dígitos: cpf = :busca já cobre
        }

        return [
            'sql' => '(' . implode(' OR ', $conds) . ')',
            'parse' => $parse,
        ];
    }

    public function confirmarPresenca($params): Read
    {
        $busca = trim((string) ($params['codigo'] ?? ''));
        $digitos = DocumentoIdentidade::onlyDigits($busca);
        $idVisita = (int) ($params['id_visita'] ?? 0);
        $filtro = $this->clausulaBuscaInscricao($busca, $digitos, true);

        $update = new Read();
        $update->FullRead(
            "UPDATE `visitas_inscricoes` SET `presenca` = 'Sim' WHERE `id_visita` = :id_visita AND {$filtro['sql']}",
            "id_visita={$idVisita}&{$filtro['parse']}"
        );

        $read = new Read();
        $read->FullRead(
            "SELECT * FROM `visitas_inscricoes` WHERE `id_visita` = :id_visita AND {$filtro['sql']}",
            "id_visita={$idVisita}&{$filtro['parse']}"
        );
        return $read;
    }

    public function InscricoesConfirmados($id_visita): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `visitas_inscricoes` WHERE `id_visita` = :id_visita AND `presenca` = 'Sim' ORDER BY nome ASC", "id_visita={$id_visita}");
        return $read;
    }

    public function InscricoesConfirmadosPalestra($id_palestra): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `palestras_participantes` WHERE `id_palestra` = :id_palestra AND `presenca` = 'Sim' ORDER BY nome ASC", "id_palestra={$id_palestra}");
        return $read;
    }

    public function confirmarPresencaPalestra($params): Read
    {
        $busca = trim((string) ($params['codigo'] ?? ''));
        $digitos = DocumentoIdentidade::onlyDigits($busca);
        $idPalestra = (int) ($params['id_palestra'] ?? 0);
        $filtro = $this->clausulaBuscaInscricao($busca, $digitos, false);

        $read = new Read();
        $read->FullRead(
            "UPDATE `palestras_participantes` SET `presenca` = 'Sim' WHERE `id_palestra` = :id_palestra AND {$filtro['sql']}",
            "id_palestra={$idPalestra}&{$filtro['parse']}"
        );
        return $read;
    }

}
