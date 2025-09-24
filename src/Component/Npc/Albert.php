<?php
namespace Games\Component\Npc;

use Jugid\Staurie\Game\Npc;
use Jugid\Staurie\Game\Position\Position;

class Albert extends Npc
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
        return 'Albert';
    }

    public function description(): string
    {
        return 'une personne lambda';
    }

    public function level(): int
    {
        return 1;
    }

    public function health_points(): int
    {
        return 25;
    }

    public function defense(): int
    {
        return 2;
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
    return "Bonjour, je suis Albert.";
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
