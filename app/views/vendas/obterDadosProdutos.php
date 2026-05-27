<?php

use app\models\CrudVendas;

$obj2 = new CrudVendas;

// Retorno JSON seguro: endpoint usado pela selecao de produto no PDV.
header('Content-Type: application/json; charset=utf-8');
echo json_encode($obj2->obterDadosProduto($_POST['idproduto'] ?? null));
