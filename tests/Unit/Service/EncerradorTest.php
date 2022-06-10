<?php

namespace Alura\Leilao\Tests\Unit\Service;

use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Service\Encerrador;
use Alura\Leilao\Service\EnviadorEmail;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EncerradorTest extends TestCase
{

    private $encerrador;
    private $fiatMobi;
    private $fiatArgo;
    /**
     * @var EnviadorEmail|MockObject
     */
    private $enviadorEmail;

    protected function setUp(): void
    {
        $this->fiatMobi = new Leilao('Fiat Mobi 2021', new \DateTimeImmutable('8 days ago'));
        $this->fiatArgo = new Leilao('Fiat Argo 2021', new \DateTimeImmutable('10 days ago'));

        $leilaoDao = $this->createMock(LeilaoDao::class);
//        $leilaoDao = $this->getMockBuilder(LeilaoDao::class)
//            ->setConstructorArgs([new \PDO('sqlite::memory:')])->getMock();
        $leilaoDao->method('recuperarNaoFinalizados')->willReturn([$this->fiatMobi, $this->fiatArgo]);
        $leilaoDao->expects($this->exactly(2))
            ->method('atualiza')
            ->withConsecutive(
                [$this->fiatMobi],
                [$this->fiatArgo]
            );

        $this->enviadorEmail = $this->createMock(EnviadorEmail::class);

        $this->encerrador = new Encerrador($leilaoDao, $this->enviadorEmail);
    }

    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $this->encerrador->encerra();

        $finalizados = [$this->fiatMobi, $this->fiatArgo];
        self::assertCount(2, $finalizados);
        self::assertTrue($finalizados[0]->estaFinalizado());
        self::assertTrue($finalizados[1]->estaFinalizado());
    }

    public function testDeveContinarOProcessamentoAoEncontrarErroAoEnviarEmail()
    {
        $exption = new \DomainException('Erro ao enviar email');
        $this->enviadorEmail->expects($this->exactly(2))
            ->method('notificarTerminoLeilao')
            ->willThrowException($exption);

        $this->encerrador->encerra();
    }

    public function testSoDeveEnviarEnviarLeilaoPorEmailAposFinalizado()
    {
        $this->enviadorEmail->expects($this->exactly(2))->method('notificarTerminoLeilao')
            ->willReturnCallback(function(Leilao $leilao) {
                static::assertTrue($leilao->estaFinalizado());
            });

        $this->encerrador->encerra();
    }
}