<?php
namespace Games\Component\Monsters;

use Jugid\Staurie\Game\Monster;

class Bébé_Zombie extends Monster
{
    public function name(): string
    {
        return 'Bébé_Zombie';
    }

    public function description(): string
    {
        return 'Un infecté pur.';
    }

    public function level(): int
    {
        return 2;
    }

    public function health_points(): int
    {
        return 5;
    }

    public function defense(): int
    {
        return 1;
    }

    public function experience(): int
    {
        return 7;
    }

    public function skills(): array
    {
        return [
            'attack' => 1,
            'dodge' => 5
        ];
    }
}
