<?php
namespace Games\Maps;

use Jugid\Staurie\Component\Map\Blueprint;
use Jugid\Staurie\Game\Position\Position;

class Hospital extends Blueprint
{
    private Position $position;

    public function __construct()
    {
        $this->position = new Position(0, 0); // Position initiale comme dans Map01
    }

    public function npcs(): array
    {
        return [];
    }

    public function items(): array
    {
        return [];
    }

   /* public function monsters(): array
    {
        return [];
    }
   */

    public function monsters(): array
    {
        return [
            new Zombie(),
            new Zombie(),
            new Zombie()
        ];
    }

    public function name(): string
    {
        return "Hôpital";
    }

    public function description(): string
    {
        return "Vous êtes dans un hôpital abandonné.";
    }

    public function position(): Position
    {
        return $this->position; // Retourne la position stockée
    }
}