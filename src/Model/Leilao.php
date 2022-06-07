<?php

namespace Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private array $lances;
    /** @var string */
    private string $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {

        if ((!empty($this->lances)) && $this->ehDoUltimoUsuario($lance)) {
            return;
        }

        $usuario = $lance->getUsuario();

        $totalLancesUsuario = array_reduce(
            $this->lances,
            function (int $acc, Lance $lanceAtual) use ($usuario) {
            if ($lanceAtual->getUsuario() === $usuario) {
                    return $acc + 1;
            }
            return $acc;
        }, 0);

        if ($totalLancesUsuario >= 5) {
            return;
        }

        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    /**
     * @param Lance $lance
     * @return bool
     */
    public function ehDoUltimoUsuario(Lance $lance): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return ($lance->getUsuario() === $ultimoLance->getUsuario());
    }
}
