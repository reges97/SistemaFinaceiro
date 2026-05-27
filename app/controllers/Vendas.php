<?php

namespace app\controllers;
use app\models\CrudVendas;

class Vendas extends CrudVendas
{

    public function login()
        {
            require_once __DIR__ . '/../views/login.php';
            require_once __DIR__ . '/../views/confmenu.php';
    
        }

        public function vendas()
        
        {
        require_once __DIR__ . '/../views/menu2.php';
         
        require_once __DIR__ . '/../views/vendas/vendas.php'; 
        require_once __DIR__ . '/../views/footer.php';


        }
    

        public function vendasprodutos()
        {
            
        require_once __DIR__ . '/../views/vendas/vendasDeProdutos.php'; 


        }

        public function tabelaVendasTemp()

        {
            require_once __DIR__ . '/../views/vendas/tabelaVendasTemp.php'; 
        }


        public function obterProduto()
        {
          
            require_once __DIR__ . '/../views/vendas/obterDadosProdutos.php'; 

        }

        public function adicionarProdutoTemp()
        {
               
          // Retorno simples para o frontend diferenciar item adicionado de validacao recusada.
          echo $this->adcionarProd() === false ? 0 : 1;  

        }


        public function limparTemp()

        {
            // Sessao protegida contra chamada duplicada ao limpar o carrinho temporario.
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

	       unset($_SESSION['tabelaComprasTemp']);
        }

        public function fecharProduto()
        {
            // Sessao protegida contra chamada duplicada ao remover item do carrinho temporario.
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $index=filter_input(INPUT_POST, 'ind', FILTER_VALIDATE_INT);
            if($index === false || !isset($_SESSION['tabelaComprasTemp'][$index])){
                echo 0;
                return;
            }
            unset($_SESSION['tabelaComprasTemp'][$index]);
            $dados=array_values($_SESSION['tabelaComprasTemp'] ?? []);
            unset($_SESSION['tabelaComprasTemp']);
            $_SESSION['tabelaComprasTemp']=$dados;
            echo 1;
                }
                
                public function criarVenda()

                {

                    // Sessao protegida contra chamada duplicada ao finalizar venda.
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }
                @$sess = $_SESSION['tabelaComprasTemp'] ?? [];
		
                if(count($sess) == 0){
                    echo 0;
                }else{
                   $result2 = $this->criar();
                   if($result2 === false || (is_array($result2) && !empty($result2['erro']))){
                    echo is_array($result2) && !empty($result2['mensagem'])
                        ? $result2['mensagem']
                        : 'Estoque insuficiente para finalizar a venda';
                    return;
                   }
                    unset($_SESSION['tabelaComprasTemp']);
                   // Retorno com id da venda para abrir o cupom automaticamente no frontend.
                   echo 'OK|' . $result2['id_venda'];
                }
                            }

        public function listarVendas()
        {
            // Historico carregado por AJAX para manter a tela de vendas no mesmo padrao do roteador.
            require_once __DIR__ . '/../views/vendas/listarVendas.php';
        }

        public function cupomVenda($idVenda = null)
        {
            // Cupom de venda interno para impressao/reimpressao; nao substitui NFC-e/NF-e.
            require_once __DIR__ . '/../views/vendas/cupomVenda.php';
        }


        public function con()
        {
            $this->conectar();
        }
    
}
