<?php

namespace Alura\Leilao\Tests\Integration\Dao;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use PHPUnit\Framework\TestCase;


class LeilaoDaoTest extends TestCase
{

    private static \PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new \PDO('sqlite::memory:');
        self::$pdo->exec('create table leiloes (
            id         INTEGER primary key,
            descricao  TEXT,
            finalizado BOOL,
            dataInicio TEXT
        );');

    }
    protected function setUp(): void
    {
        self::$pdo->beginTransaction();
    }

    public function testBuscaLeiloesNaoFinalizados()
    {
        $leilaoFusca = new Leilao('Fusca 1947');
        $leilaoFiat = new Leilao('Fiat Uno');
        $leilaoDao = new LeilaoDao(self::$pdo);

        $leilaoFiat->finaliza();
        $leilaoDao->salva($leilaoFusca);
        $leilaoDao->salva($leilaoFiat);
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Fusca 1947', $leiloes[0]->recuperarDescricao());
    }

    public function testBuscaLeiloesFinalizados()
    {
        $leilaoFusca = new Leilao('Fusca 1947');
        $leilaoFiat = new Leilao('Fiat Uno');
        $leilaoDao = new LeilaoDao(self::$pdo);

        $leilaoFiat->finaliza();
        $leilaoDao->salva($leilaoFusca);
        $leilaoDao->salva($leilaoFiat);
        $leiloes = $leilaoDao->recuperarFinalizados();

        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Fiat Uno', $leiloes[0]->recuperarDescricao());
    }

    protected function tearDown(): void
    {
        self::$pdo->rollBack();
    }
}