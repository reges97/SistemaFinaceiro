<?php

namespace app\models;

class CrudProcessa extends Connection
{
    public function processa()

    {
        $access_token   = 'TEST-7347674576608536-062010-03b1f94fe08ac9788601199e3588086e-1167576986';
        $public_key     = 'TEST-8b572bb4-5fd6-4ab0-a459-5c20e8ec7286';
    

$json       = file_get_contents('php://input');
$result_request  = json_decode($json);
$id_reg = $result_request->id_reg;
$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
         "transaction_amount": '.(float)$result_request->transaction_amount.',
         "token": "'.$result_request->token.'",
         "description": "'.$result_request->description.'",
         "installments": '.$result_request->installments.',
         "payment_method_id": "'.$result_request->payment_method_id.'",
         "issuer_id": '.$result_request->issuer_id.',
         "payer": {
           "email": "'.$result_request->payer->email.'"
         }
   }',
    CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'content-type: application/json',
        'Authorization: Bearer '.$access_token
    ),
    ));
    $response = curl_exec($curl);
    $resultado = json_decode($response);
    
curl_close($curl);
$status = $resultado->status;

$pdo = $this->connect(); 

$sql=$pdo->query("INSERT INTO status(nome, status, id_venda) VALUES('".$id_reg."','".$resultado->status."','".$resultado->id."')");
$res = $sql->fetchAll(\PDO::FETCH_ASSOC);
return $res;


    }
}
