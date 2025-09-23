<?php
namespace Games\Npc;

use Jugid\Staurie\Game\Npc;

class Albert extends Npc
{
    public function name(): string
    {
        return 'ALice';
    }

    public function description(): string
    {
        return 'une princesse avec des secret';
    }

    public function level(): int
    {
        return 1;
    }

    public function health_points(): int
    {
        return 50;
    }

    public function defense(): int
    {
        return 0;
    }



    public function skills(): array
    {
        return [
            'attack' => 5,
            'dodge' => 3
        ];
    }

    public function speak(): string|array
{
    return "hi hi hi, je suis la princesse Alice.";
}

}
