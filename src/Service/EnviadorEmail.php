<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Leilao;

class EnviadorEmail
{

    public function notificarTerminoLeilao(Leilao $leilao)
    {
        $sucesso = mail('usuario@email.com',
            'Leilao Finalizado',
            'O Leilao para' . $leilao->recuperarDescricao(). ' foi finalizado.');

        if (!$sucesso) {
            throw  new \DomainException('Erro ao enviar email');
        }
    }
}