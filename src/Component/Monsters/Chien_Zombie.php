<?php

namespace Games\Component\Monsters;

use Jugid\Staurie\Game\Monster;

class Chien_Zombie extends Monster
{
    public function name(): string
    {
        return 'Chien_Zombie';
    }

    public function description(): string
    {
        return 'Un infecté pur, rapide et agressif.';
    }

    public function level(): int
    {
        return 5; // Peut ajuster selon l’équilibrage du jeu
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
