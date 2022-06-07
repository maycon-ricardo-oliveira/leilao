<?php
namespace Leilao\Tests\Model;

use Leilao\Model\Lance;
use Leilao\Model\Leilao;
use Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    /**
     * @param int $quantidadeLances
     * @param Leilao $leilao
     * @param array $valores
     * @return void
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(
        int $quantidadeLances,
        Leilao $leilao,
        array $valores
    ) {
        static::assertCount($quantidadeLances, $leilao->getLances());
        foreach ($valores as $key => $valor) {
            static::assertEquals($valor, $leilao->getLances()[$key]->getValor());
        }
    }

    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        static::expectException(\DomainException::class);
        static::expectExceptionMessage('Usuário não pode propor dois lances consecutivos');
        $leilao = new Leilao('Kombi');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));
    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        static::expectException(\DomainException::class);
        static::expectExceptionMessage('Usuário não pode propor mais de 5 lances por leilão');

        $leilao = new Leilao('Brasilia Amarela');
        $joao = new Usuario('Jao');
        $maria = new Usuario('Maria');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 1500));

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($maria, 3500));

        $leilao->recebeLance(new Lance($joao, 4000));
        $leilao->recebeLance(new Lance($maria, 4500));

        $leilao->recebeLance(new Lance($joao, 5000));
        $leilao->recebeLance(new Lance($maria, 5500));

        $leilao->recebeLance(new Lance($joao, 6000));

    }

    public function testLeilaoNaoDeveReceberLancesAposFinalizado()
    {
        static::expectException(\DomainException::class);
        static::expectExceptionMessage('Leilão não pode receber lances após ser finalizado');
        $leilao = new Leilao('Gol Quadrado');
        $leilao->recebeLance(new Lance(new Usuario('José'), 2000));
        $leilao->finaliza();
        $leilao->recebeLance(new Lance(new Usuario('Ana'), 2500));

    }

    public function geraLances()
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilaoComDoisLances = new Leilao('Palio 2003');
        $leilaoComDoisLances->recebeLance(new Lance($joao, 1000));
        $leilaoComDoisLances->recebeLance(new Lance($maria, 2000));

        $leilaoComUmLance = new Leilao('Fusca 1972');
        $leilaoComUmLance->recebeLance(new Lance($maria, 5000));

        return [
            '2-lances' => [2, $leilaoComDoisLances, [1000, 2000]],
            '1-lance' => [1, $leilaoComUmLance, [5000]]
        ];
    }

}