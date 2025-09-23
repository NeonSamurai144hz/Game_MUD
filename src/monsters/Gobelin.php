<?php
namespace Games\Monsters;

use Jugid\Staurie\Game\Monster;

class Goblin extends Monster
{
    public function name(): string
    {
        return 'Goblin';
    }

    public function description(): string
    {
        return 'Un petit roumain malicieux et rapide.';
    }

    public function level(): int
    {
        return 1;
    }

    public function health_points(): int
    {
        return 15;
    }

    public function defense(): int
    {
        return 3;
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
}
