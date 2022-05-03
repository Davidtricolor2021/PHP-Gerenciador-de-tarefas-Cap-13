<?php

class RepositorioTarefas
{
    private $conexao;

    public function __construct(mysqli $conexao)
    {
        $this->conexao = $conexao;
    }

    public function salvar(Tarefa $tarefa)
    {
        $nome = $tarefa->getNome();
        $descricao = $tarefa->getDescricao();
        $prioridade = $tarefa->getPrioridade();
        $prazo = $tarefa->getPrazo();
        $concluida = ($tarefa->getConcluida()) ? 1 : 0;

        if (is_object($prazo)) {
            $prazo = "'{$prazo->format('Y-m-d')}'";
        } elseif ($prazo == '') {
            $prazo = 'NULL';
        } else {
            $prazo = "'{$prazo}'";
        }

        $sqlGravar = " INSERT INTO tarefas
                        (nome, descricao, prioridade, prazo, concluida)
                        VALUES
                        (
                            '{$nome}',
                            '{$descricao}',
                            {$prioridade},
                            {$prazo},
                            {$concluida}
                        )
        ";

        $this->conexao->query($sqlGravar);
    }

    public function atualizar(Tarefa $tarefa)
    {
        $id = $tarefa->getId();
        $nome = $tarefa->getNome();
        $descricao = $tarefa->getDescricao();
        $prioridade = $tarefa->getPrioridade();
        $prazo = $tarefa->getPrazo();
        $concluida = ($tarefa->getConcluida()) ? 1 : 0;

        if (is_object($prazo)) {
            $prazo = "'{$prazo->format('Y-m-d')}'";
        }   elseif ($prazo == '') {
            $prazo = 'NULL';
        }   else {
            $prazo = "'{$prazo}'";
        }

        $sqlEditar = " UPDATE tarefas SET
                        nome = '{$nome}',
                        descricao = '{$descricao}',
                        prioridade = {$prioridade},
                        prazo = {$prazo},
                        concluida = {$concluida}
                        WHERE id = {$id}
        ";

        $this->conexao->query($sqlEditar);
    }

    public function buscar(int $tarefa_id)
    {
        //Busca uma ou todas as tarefas no banco
    }

    public function remover(int $tarefa_id)
    {
        //Remove uma tarefa do banco
    }
}
