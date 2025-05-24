<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class EmailsPainel extends Model
{
    protected $table = 'config_emails';

  
    public function getByTipo($tipo)
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM {$this->table} WHERE email_tipo = :tipo LIMIT 1", "tipo={$tipo}");
        return $read->getResultSingle();
    }

    public function salvar($tipo, $lista_emails)
    {
        $existe = $this->getByTipo($tipo);
        if ($existe) {
            $update = new Update();
            $update->ExeUpdate($this->table, ['lista_emails' => $lista_emails], 'WHERE email_tipo = :tipo', "tipo={$tipo}");
            return $update->getResult();
        } else {
            $create = new Create();
            $create->ExeCreate($this->table, ['email_tipo' => $tipo, 'lista_emails' => $lista_emails]);
            return $create->getResult();
        }
    }
} 