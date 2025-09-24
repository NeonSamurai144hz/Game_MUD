<?php

namespace Games\Component\Maps;

use Jugid\Staurie\Component\Map\Blueprint;
use Jugid\Staurie\Game\Position\Position;
use Games\Component\Npc\Albert;
use Games\Component\Monsters\Zombie;
use Games\Component\Monsters\Chien_Zombie;
use Games\Component\Items\Munition;
use Games\Component\Items_Equipable\Aka;

class Rue extends Blueprint
{

    private Position $position;

    public function __construct()
    {
        $this->position = new Position(0, 1);
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
    public function aka(): array
    {
        return [
            new Aka(),
        ];
    }

    public function monsters(): array
    {
        $monsters = [];
        $nbZombie = 10;
        $nbChienZombie = 5;
        for ($i = 0; $i < $nbZombie; $i++) {
            $zombie = new Zombie();
            $zombie->setPosition(new Position(rand(0, 10), rand(0, 10)));
            $monsters[] = $zombie;
        }

        for ($i = 0; $i < $nbChienZombie; $i++) {
            $chien_zombie = new Chien_Zombie();
            $chien_zombie->setPosition(new Position(rand(0, 10), rand(0, 10)));
            $monsters[] = $chien_zombie;
        }
        return $monsters;
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