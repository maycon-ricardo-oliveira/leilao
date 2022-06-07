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
            throw new \DomainException('Usuário não pode propor dois lances consecutivos');
        }

        $totalLancesUsuario = $this->quantitadeDeLancesPorUsuario($lance->getUsuario());

        if ($totalLancesUsuario >= 5) {
            throw new \DomainException('Usuário não pode propor mais de 5 lances por leilão');
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

    /**
     * @param Usuario $usuario
     * @return int
     */
    public function quantitadeDeLancesPorUsuario(Usuario $usuario): int
    {
        return array_reduce(
            $this->lances,
            function (int $acc, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() === $usuario) {
                    return $acc + 1;
                }
                return $acc;
            },
        0
        );
    }
}
