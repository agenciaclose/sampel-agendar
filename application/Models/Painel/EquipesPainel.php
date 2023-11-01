<?php

    namespace Agencia\Close\Models\Painel;

    use Agencia\Close\Conn\Read;
    use Agencia\Close\Models\Model;

    class EquipesPainel extends Model
    {
    	
        public function getEquipesList()
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM usuarios ORDER BY `id` DESC");
            return $read;
        }

        public function getEquipesEditar($id)
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM usuarios WHERE id = :id ORDER BY `id` DESC", "id={$id}");
            return $read;
        }

    }