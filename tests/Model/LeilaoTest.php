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
    public function testLeilaoDeveReceberLances(int $quantidadeLances, Leilao $leilao, array $valores)
    {

        static::assertCount($quantidadeLances, $leilao->getLances());

        foreach ($valores as $key => $valor) {
            static::assertEquals($valor, $leilao->getLances()[$key]->getValor());
        }

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