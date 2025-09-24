<?php

namespace Games\Maps;

use Jugid\Staurie\Component\Map\Blueprint;
use Jugid\Staurie\Game\Position\Position;
use Games\Component\Monsters\Zombie;
use Games\Component\Monsters\Chien_Zombie;
use Games\Component\Monsters\Nemesis;
use Games\Component\Monsters\Bebe_Zombie;
use Games\Component\Items\Munition;
use Games\Component\Items_Equipable\Aka;
use Games\Component\Items_Equipable\Pistolet;

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
        return [
            new Munition(),
            new Aka(),
            new Aka(),
            new Pistolet(),
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

        for ($i = 0; $i < 3; $i++) {
            $chien_zombie = new Chien_Zombie();
            $chien_zombie->setPosition(new Position(rand(0, 10), rand(0, 10)));
            $monsters[] = $chien_zombie;
        }

        for ($i = 0; $i < 2; $i++) {
            $bebe_zombie = new Bebe_Zombie();
            $bebe_zombie->setPosition(new Position(rand(0, 10), rand(0, 10)));
            $monsters[] = $bebe_zombie;
        }

        $nemesis = new Nemesis();
        $nemesis->setPosition(new Position(rand(0, 10), rand(0, 10)));
        $monsters[] = $nemesis;


        return $monsters;
    }

    public function name(): string
    {
        return "Base militaire";
    }

    public function description(): string
    {
        return "Vous êtes dans une base militaire abandonnée. Des bâtiments en ruine "
            . "et des véhicules militaires délabrés sont éparpillés partout. "
            . "L'atmosphère est lourde et silencieuse, avec seulement le bruit du vent "
            . "qui souffle à travers les structures abandonnées.";
    }

    public function position(): Position
    {
        return $this->position;
    }
}
