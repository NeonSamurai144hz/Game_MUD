<?php
namespace Games\Monsters;

use Jugid\Staurie\Game\Monster;

class Zombie extends Monster
{
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
}
