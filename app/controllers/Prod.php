<?php

namespace app\controllers;
use app\models\CrudProdutos;

class Prod extends CrudProdutos
{
    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';
        

    }

    public function produtos()
    {
       require_once __DIR__ . '/../views/menu2.php';
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/produtos/produtos.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function listar()
    {
        require_once __DIR__ . '/../views/produtos/listarProd.php';
    }

    public function cadastrar()
    {

    }

    public function deletar()
    {
         
    }

    public function mudar()
    {
        $muda = $this->mudarStatus();
    }

    public function selecao()

    {
        $selecao = $this->selecaoProd();
    }

    public function selecao2()
    {
     $this->SelecaoForne();
    }

    public function comprar()
    {

        $comprar = $this->comprarProd();
    }

    public function con()
    {
        $this->conectar();
    }

    public function relProdutos_class()
    {

        require_once __DIR__ . '/../../config/dompdf/autoload.inc.php';
 
        require_once __DIR__ . '/../views/rel/relProdutos_class.php';

       
       
    }

    public function relProdutos()
    {      
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        $data_hoje = date("d/m/Y H:i:s");
        
        //VARIAVEIS GLOBAIS
        $nome_sistema = "SISTEMA FINANCEIRO";
        
        $url_sistema = "http://localhost/sistemaFinanceiro/" ;//é preciso configurar essa url para gerar os relatorios, ela deve apontar para a raiz do seu dominio (https://www.google.com/) com a barra no final e o protocolo http ou https de acordo com seu dominio no inicio.
        
        $telefone_sistema = "(11) 95650-5900";
        $endereco_sistema = "Rua Gruarantá 597 ";
        //$rodape_relatorios = "Sistema Desenvolvido por Reginaldo Roberto Ribeiro!";


               
       require_once __DIR__ . '/../views/rel/relProdutos.php';


    }



}
