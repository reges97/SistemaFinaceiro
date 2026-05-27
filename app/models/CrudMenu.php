<?php

namespace app\models;

class CrudMenu extends Connection
{
    public function listaMenu()
    {
        $pdo = $this->connect();

        // Permissoes simplificadas: a tela usa menu principal; submenu antigo nao existe em algumas bases.
        $query = $pdo->query("SELECT M.id_menu, M.menu from menu AS M order by M.id_menu ");
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);

         return $res;

    }


    

}
