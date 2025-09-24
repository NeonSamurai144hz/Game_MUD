<?php
namespace Games\Component\Monsters;

use Jugid\Staurie\Game\Monster;
use Jugid\Staurie\Game\Position\Position;


class Zombie extends Monster
{

  /**
     * Position actuelle d'Albert
     * @var Position|null
     */
    private $position;

    public function __construct()
    {
        $this->position = null;
    }

    public function name(): string
    {
        return 'Zombie';
    }

    public function description(): string
    {
        return 'Un infecté pur.';
    }

    public function level(): int
    {
        return 1;
    }

    public function health_points(): int
    {
        return 10;
    }

    public function defense(): int
    {
        return 4;
    }

    public function experience(): int
    {
        return 10;
    }

    public function skills(): array
    {
        return [
            'attack' => 4,
            'dodge' => 2
        ];
    }
    public function setPosition(Position $position): void
    {
        $this->position = $position;
    }


    public function position(): ?Position
    {
        return $this->position;
    }
}
