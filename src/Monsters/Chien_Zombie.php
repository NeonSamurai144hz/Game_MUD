<?php
namespace Games\Monsters;

use Jugid\Staurie\Game\Monster;

class Chien_Zombie extends Monster
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
        return 52;
    }

    public function health_points(): int
    {
        return 12;
    }

    public function defense(): int
    {
        return 8;
    }

    public function experience(): int
    {
        return 10;
    }

    public function skills(): array
    {
        return [
            'attack' => 8,
            'dodge' => 10
        ];
    }
}
