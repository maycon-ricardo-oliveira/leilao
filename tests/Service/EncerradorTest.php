<?php

namespace Alura\Leilao\Tests\Service;



use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Service\Encerrador;
use PHPUnit\Framework\TestCase;

class EncerradorTest extends TestCase
{

    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $fiatMobi = new Leilao('Fiat Mobi 2021', new \DateTimeImmutable('8 days ago'));
        $fiatArgo = new Leilao('Fiat Argo 2021', new \DateTimeImmutable('10 days ago'));

        $leilaoDao = $this->createMock(LeilaoDao::class);
//        $leilaoDao = $this->getMockBuilder(LeilaoDao::class)
//            ->setConstructorArgs([new \PDO('sqlite::memory:')])->getMock();
        $leilaoDao->method('recuperarFinalizados')->willReturn([$fiatMobi, $fiatArgo]);
        $leilaoDao->method('recuperarNaoFinalizados')->willReturn([$fiatMobi, $fiatArgo]);
        $leilaoDao->expects($this->exactly(2))
            ->method('atualiza')
            ->withConsecutive(
                [$fiatMobi],
                [$fiatArgo]
            );

        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();

        $finalizados = [$fiatMobi, $fiatArgo];

        self::assertCount(2, $finalizados);
        self::assertTrue($finalizados[0]->estaFinalizado());
        self::assertTrue($finalizados[1]->estaFinalizado());
    }
}