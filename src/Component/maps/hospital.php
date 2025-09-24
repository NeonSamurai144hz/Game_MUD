<?php

namespace Games\Component\Maps;

use Jugid\Staurie\Component\Map\Blueprint;
use Jugid\Staurie\Game\Position\Position;
use Games\Component\Npc\Albert;
use Games\Component\Monsters\Zombie;
use Games\Component\Items\Munition;

class Hopital extends Blueprint
{
    private Position $position;

    public function __construct()
    {
        $this->position = new Position(0, 0);
    }

    public function name(): string
    {
        return 'Hôpital';
    }

    public function description(): string
    {
        return 'Un hôpital abandonné, sombre et mystérieux.';
    }

    public function position(): Position
    {
        return $this->position;
    }

    public function npcs(): array
    {
        $albert = new Albert();
        $albert->setPosition(new Position(rand(0, 10), rand(0, 10)));

        return [$albert];
    }

    public function items(): array
    {
        return [
            new Munition(),
        ];
    }

    public function monsters(): array
    {
        $monsters = [];
        for ($i = 0; $i < 5; $i++) {
            $zombie = new Zombie();
            $zombie->setPosition(new Position(rand(0, 10), rand(0, 10)));
            $monsters[] = $zombie;
        }
        return $monsters;
    }
}