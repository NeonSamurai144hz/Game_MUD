<?php

namespace Games\Maps;

use Jugid\Staurie\Component\Map\Blueprint;
use Jugid\Staurie\Game\Position\Position;

class Rue extends Blueprint
{

    private Position $position;

    public function __construct()
    {
        $this->position = new Position(0, 1);
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
        return "Ruelle sombre";
    }
    public function description(): string
    {
        return "Vous êtes dans une ruelle sombre et étroite. Il y a des poubelles renversées 
        partout et l'odeur est nauséabonde.Vous pouvez entendre des bruits étranges 
        venant des coins sombres.";
    }
    public function position(): Position
    {
        return $this->position;
    }

}
