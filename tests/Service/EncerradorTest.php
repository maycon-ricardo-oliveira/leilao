<?php

namespace Alura\Leilao\Tests\Service;



use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Service\Encerrador;
use PHPUnit\Framework\TestCase;

class LeilaoDaoMock extends LeilaoDao {

    private $leiloes = [];

    public function salva(Leilao $leilao): void
    {
        $this->leiloes[] = $leilao;
    }

    /**
     * @return array|Leilao[]
     */
    public function recuperarNaoFinalizados(): array
    {
        return array_filter($this->leiloes, function (Leilao $leilao) {
           return !$leilao->estaFinalizado();
        });
    }

    public function atualiza(Leilao $leilao): void
    {

    }

    /**
     * @return array|Leilao[]
     */
    public function recuperarFinalizados(): array
    {
        return array_filter($this->leiloes, function (Leilao $leilao) {
            return $leilao->estaFinalizado();
        });
    }

}

class EncerradorTest extends TestCase
{

    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $fiatMobi = new Leilao('Fiat Mobi 2021', new \DateTimeImmutable('8 days ago'));
        $fiatArgo = new Leilao('Fiat Argo 2021', new \DateTimeImmutable('10 days ago'));

        $leilaoDao = new LeilaoDaoMock();
        $leilaoDao->salva($fiatMobi);
        $leilaoDao->salva($fiatArgo);

        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();

        $finalizados = $leilaoDao->recuperarFinalizados();

        self::assertCount(2, $finalizados);
        self::assertEquals('Fiat Mobi 2021', $finalizados[0]->recuperarDescricao());
        self::assertEquals('Fiat Argo 2021', $finalizados[1]->recuperarDescricao());
    }
}