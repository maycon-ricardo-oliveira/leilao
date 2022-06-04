<?php

namespace Leilao\Tests\Service;

use Leilao\Model\Lance;
use Leilao\Model\Leilao;
use Leilao\Model\Usuario;
use Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    public function testAvaliadorDeveEncontrarOMaiorValorDeLancesEmOrdemCrescente()
    {

        // Arrange  -   Given
        $leilao = $this->leilaoEmOrdemCrescente();

        // Act  -   When
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        // Assert   -   Then
        $this->assertEquals(2000, $maiorValor);
    }
    public function testAvaliadorDeveEncontrarOMaiorValorDeLancesEmOrdemDecrescente()
    {

        // Arrange  -   Given
        $leilao = $this->leilaoEmOrdemDecrescente();

        // Act  -   When
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        // Assert   -   Then
        $this->assertEquals(2000, $maiorValor);
    }
    public function testAvaliadorDeveEncontrarOMenorValorDeLancesEmOrdemDecrescente()
    {

        // Arrange  -   Given
        $leilao = $this->leilaoEmOrdemDecrescente();

        // Act  -   When
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $menorValor = $leiloeiro->getMenorValor();

        // Assert   -   Then
        $this->assertEquals(1000, $menorValor);
    }
    public function testAvaliadorDeveEncontrarOMenorValorDeLancesEmOrdemCrescente()
    {

        // Arrange  -   Given
        $leilao = $this->leilaoEmOrdemCrescente();

        // Act  -   When
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $menorValor = $leiloeiro->getMenorValor();

        // Assert   -   Then
        $this->assertEquals(1000, $menorValor);
    }

    public function testAvaliadorDeveBuscar3MaioresValores()
    {
        $leilao = $this->leilaoEmOrdemAleatoria();

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        $maiores = $leiloeiro->getMaioresLances();

        static::assertCount(3, $maiores);
        static::assertEquals(2000, $maiores[0]->getValor());
        static::assertEquals(1700, $maiores[1]->getValor());
        static::assertEquals(1500, $maiores[2]->getValor());
    }

    public function leilaoEmOrdemCrescente()
    {

        $leilao = new Leilao('Palio 2003');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($joao, 1500));
        $leilao->recebeLance(new Lance($jorge, 1700));
        $leilao->recebeLance(new Lance($maria, 2000));

        return $leilao;
    }

    public function leilaoEmOrdemDecrescente()
    {

        $leilao = new Leilao('Palio 2003');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        $leilao->recebeLance(new Lance($maria, 2000));
        $leilao->recebeLance(new Lance($jorge, 1700));
        $leilao->recebeLance(new Lance($joao, 1500));
        $leilao->recebeLance(new Lance($ana, 1000));

        return $leilao;
    }

    public function leilaoEmOrdemAleatoria()
    {

        $leilao = new Leilao('Palio 2003');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        $leilao->recebeLance(new Lance($jorge, 1700));
        $leilao->recebeLance(new Lance($maria, 2000));
        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($joao, 1500));

        return $leilao;
    }
}