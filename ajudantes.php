<?php

use PHPMailer\PHPMailer\PHPMailer;

function traduz_data_para_banco($data)
{
    if ($data == "") {
        return "";
    }

    $partes = explode("/", $data);

    if (count($partes) != 3){
        return $data;
    }

    $objeto_data = DateTime::createFromFormat('d/m/Y', $data);

    return $objeto_data->format('Y-m-d');
}

function traduz_data_br_para_objeto($data)
{
    if ($data == "") {
        return "";
    }

    $dados = explode("/", $data);

    if (count($dados) != 3) {
        return $data;
    }

    return  DateTime::createFromFormat('d/m/Y', $data);
}

function traduz_data_para_exibir($data)
{
    if ($data == "" OR $data == "0000-00-00") {
        return "";
    }

    $partes = explode("-", $data);

    if (count($partes) != 3) {
        return $data;
    }

    $objeto_data = DateTime::createFromFormat('Y-m-d', $data);

    return $objeto_data->format('d/m/Y');
}

function traduz_prioridade($codigo)
{
    $prioridade = '';

    switch ($codigo) {
        case 1:
            $prioridade = 'Baixa';
            break;
        case 2:
            $prioridade = 'Média';
            break;
        case 3:
            $prioridade = 'Alta';
            break;        
    }

    return $prioridade;
}

function traduz_concluida($concluida)
{
    if($concluida == 1) {
        return 'Sim';
    }
    return 'Não';
}

function tem_post()
{
    if (count($_POST) > 0) {
        return true;
    }

    return false;
}

function validar_data($data)
{
    $padrao = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';
    $resultado = preg_match($padrao, $data);

    if ($resultado == 0) {
        return false;
    }

    $dados = explode ('/', $data);

    $dia = $dados[0];
    $mes = $dados[1];
    $ano = $dados[2];

    return checkdate($mes, $dia, $ano);
}

function tratar_anexo($anexo) {
    $padrao = '/^.+(\.pdf|\.zip)$/';
    $resultado = preg_match($padrao, $anexo['name']);

    if ($resultado == 0) {
        return false;
    }

    move_uploaded_file($anexo['tmp_name'], "anexos/{$anexo['name']}");

    return true;
}

function enviar_email(Tarefa $tarefa)
{
    include "bibliotecas/PHPMailer/inc.php";

    $corpo = preparar_corpo_email($tarefa);

    $email = new PHPMailer();

    $email->isSMTP();
    $email->Host = "smtp.gmail.com";
    $email->Port = 587;
    $email->SMTPSecure = 'tls';
    $email->SMTPAuth = true;
    $email->Username = "testesdavidphp@gmail.com";
    $email->Password = "123Testesdavidphp@";
    $email->setFrom("testesdavidphp@gmail.com", "Avisador de Tarefas");
    $email->addAddress(EMAIL_NOTIFICACAO);
    $email->Subject = "Aviso de tarefa: {$tarefaa->getNome()}";
    $email->msgHTML($corpo);

    foreach ($tafera->getAnexos() as $anexo) {
        $email->addAttachment("anexos/{$anexo->getArquivo()}");
    }

    if (! $email->send()) {
        gravar_log($email->ErrorInfo);
    }
}

function preparar_corpo_email(Tarefa $anexo)
{
    ob_start();
    include "template_email.php";

    $corpo = ob_get_contents();

    ob_end_clean();

    return $corpo;
}

function gravar_log($mensagem)
{
    $datahora = date("Y-m-d H:i:s");
    $mensagem = "{$datahota} {$mensagem}\n";

    file_put_contents("mensagens.log", $mensagem, FILE_APPEND);
}
