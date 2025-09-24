<?php
namespace Games\Maps;

use Jugid\Staurie\Component\Map\Blueprint;
use Jugid\Staurie\Game\Position\Position;

class Foret extends Blueprint
{
    private Position $position;

    public function __construct()
    {
        $this->position = new Position(1, 0);
    }
    public function npcs(): array
    {
        return [];
    }

    public function items(): array
    {
        return [];
    }
    public function monsters(): array
    {
        return [];
    }

    public function name(): string
    {
        return "Forêt dense";
    }
    public function description(): string
    {
        return "Vous êtes dans une forêt dense. Les arbres sont hauts et épais, 
        avec des feuilles qui forment un toit presque impénétrable au-dessus de vous. 
        Le sol est couvert de feuilles mortes et de branches cassées, et l'air est frais 
        et humide.";
    }

    public function position(): Position
    {
        return $this->position;
    }
}
