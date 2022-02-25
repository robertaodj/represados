<?php
$status = ['info' => '', 'alert' => 'alert-danger', 'icon' => 'alert-circle-outline', 'display' => 'd-none'];

if (isset($_GET['status'])) {

    $status['display'] = 'd-block';

    switch ($_GET['status']) {
        case 'erro1':
            $status['info'] = "Não foi possível enviar o arquivo, tente novamente.";
            break;

        case 'erro2':
            $status['info'] = "Não foi possível enviar o arquivo, tente novamente.";
            break;

        case 'erro3':
            $status['info'] = "Arquivo enviado com outro formato. Formato permitido: <strong>.csv <strong>";
            break;

        case 'erro4':
            $status['info'] = "Arquivo enviado muito grande. Por favor, envie um menor que 2MB.";
            break;

        case 'erro5':
            $status['info'] = "Arquivo enviado esta vázio.";
            break;

        case 'erro6':
            $status['info'] = "Nenhum arquivo foi enviado.";
            break;
        
        case 'enviado':
            $status['info'] = "Arquivo enviado com sucesso.";
            $status['alert'] = "alert-success";
            $status['icon'] = "checkmark-circle-outline";
            break;
    }

} 
?>