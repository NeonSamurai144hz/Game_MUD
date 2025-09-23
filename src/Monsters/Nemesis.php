<?php
namespace Games\Monsters;

use Jugid\Staurie\Game\Monster;

class Nemesis extends Monster
{
    public function name(): string
    {
        return 'Nemesis';
    }

    public function description(): string
    {
        return 'Nemesis,run!!!!!';
    }

    public function level(): int
    {
        return 100;
    }

    public function health_points(): int
    {
        return 900;
    }

    public function defense(): int
    {
        return 900;
    }

    public function experience(): int
    {
        return 10;
    }

    public function skills(): array
    {
        return [
            'attack' => 10,
            'dodge' => 15
        ];
    }
}
