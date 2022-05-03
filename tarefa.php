<?php

require "config.php";
require "banco.php";
require "ajudantes.php";

$tarefa = buscar_tarefa($conexao, $_GET['id']);

if (! is_array($tarefa)) {
    http_response_code(404);
    echo "Tarefa não encontrada.";
    die();
}

$tem_erros = false;
$erros_validacao = array();

if (tem_post()) {
    // upload dos anexos
    $tarefa_id = $_POST['tarefa_id'];

    if (! array_key_exists('anexo', $_FILES)) {
        $tem_erros = true;
        $erros_validacao['anexo'] = 'Você deve selecionar um arquivo para anexar';
    } else {
        if (tratar_anexo($_FILES['anexo'])) {
            $nome = $_FILES['anexo']['name'];
            $anexo = ['tarefa_id' => $tarefa_id, 'nome' => substr($nome, 0, -4), 'arquivo' => $nome, ];
        } else {
            $tem_erros = true;
            $erros_validacao['anexo'] = 'Envie anexos nos formatos zip ou pdf';
        }
    }

    if (! $tem_erros) {
        gravar_anexo($conexao, $anexo);
    }
}

$anexos = buscar_anexos($conexao, $_GET['id']);

require "template_tarefa.php";
