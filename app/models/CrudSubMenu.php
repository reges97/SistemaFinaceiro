<?php

namespace app\models;

class CrudSubMenu extends Connection
{

    public function listaSubMenu()
    {
        
            $pdo = $this->connect();
    
            $query = $pdo->query("SELECT
            M.id_menu, M.menu, 
            SB.id_sub_menu, SB.nome_sub_menu
            from submenu AS SB
            INNER JOIN menu AS M ON  M.id_menu = SB.id_menu
            
            order by M.id_menu ");
            $res = $query->fetchAll(\PDO::FETCH_ASSOC);
    
             return $res;
    
        
    
    }

    } 
