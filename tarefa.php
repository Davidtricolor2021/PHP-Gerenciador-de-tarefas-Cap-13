<?php

require "config.php";
require "banco.php";
require "ajudantes.php";
require "classes/Tarefa.php";
require "classes/Anexo.php";
require "classes/RepositorioTarefas.php";

$repositorio_tarefas = new RepositorioTarefas($mysqli);

$tarefa = $repositorio_tarefas->buscar($_GET['id']);

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
        $dados_anexo = $_FILES['anexo'];

        if (tratar_anexo($dados_anexo)) {
            $anexo = new Anexo();
            $anexo->setTarefaId($tarefa_id);
            $anexo->setNome($dados_anexo['name']);
            $anexo->setArquivo($dados_anexo['name']);
        } else {
            $tem_erros = true;
            $erros_validacao['anexo'] = 'Envie apenas anexos nos formatos zip ou pdf';
        }
    }

    if (! $tem_erros) {
        $repositorio_tarefas->salvar_anexo($anexo);
    }
}

require "template_tarefa.php";
