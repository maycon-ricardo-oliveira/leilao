<?php

namespace Alura\Leilao\Tests\Integration\Dao;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use PHPUnit\Framework\TestCase;


class LeilaoDaoTest extends TestCase
{

    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = ConnectionCreator::getConnection();
        $this->pdo->beginTransaction();
    }

    public function testInsercaoEBuscaDevemFuncionar()
    {

        $leilao = new Leilao('Fusca 1947');
        $leilaoDao = new LeilaoDao($this->pdo);

        $leilaoDao->salva($leilao);

        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);

        self::assertSame('Fusca 1947', $leiloes[0]->recuperarDescricao());
    }

    protected function tearDown(): void
    {
        $this->pdo->rollBack();
    }
}