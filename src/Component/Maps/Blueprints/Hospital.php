<?php

namespace Games\Component\Maps\Blueprints;

use Jugid\Staurie\Component\Map\Blueprint;
use Jugid\Staurie\Game\Position\Position;

class Hospital extends Blueprint
{
    public function npcs(): array
    {
        return [
            // You can add NPCs here later
            // new \Games\Component\Npc\Alice()
        ];
    }

    public function items(): array
    {
        return [
            // Add items available in this room
            // new \Games\Component\Items\Munition()
        ];
    }

    public function monsters(): array
    {
        return [
            // Add monsters in this room
            // new \Games\Component\Monsters\Zombie()
        ];
    }

    public function name(): string
    {
        return "Hospital Room";
    }

    public function description(): string
    {
        return "You are in a dimly lit hospital room. Medical equipment lies scattered around, " .
            "and there are dark stains on the white tiles. A single flickering light bulb " .
            "casts eerie shadows on the walls. There's a door to the north leading to a corridor, " .
            "and a window to the east, though it's boarded up from the outside.";
    }

    public function position(): Position
    {
        return new Position(0, 0);
    }
}