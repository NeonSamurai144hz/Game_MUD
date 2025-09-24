<?php

namespace Games\Maps;

use Jugid\Staurie\Component\Map\Blueprint;
use Jugid\Staurie\Game\Position\Position;

class BM extends Blueprint
{
    private Position $position;

    public function __construct()
    {
        $this->position = new Position(1, 1);
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
        return "Base militaire";
    }
    public function description(): string
    {
        return "Vous êtes dans une base militaire abandonnée. Des bâtiments en ruine 
        et des véhicules militaires délabrés sont éparpillés partout. L'atmosphère est 
        lourde et silencieuse, avec seulement le bruit du vent qui souffle à travers les 
        structures abandonnées.";
    }

    public function position(): Position
    {
        return $this->position;
    }
}
