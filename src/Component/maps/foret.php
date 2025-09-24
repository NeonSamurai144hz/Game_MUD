<?php
namespace Games\Maps;

use Jugid\Staurie\Component\Map\Blueprint;
use Jugid\Staurie\Game\Position\Position;
use Games\Component\Monsters\Zombie;
use Games\Component\Monsters\Chien_Zombie;
use Games\Component\Monsters\Nemesis;
use Games\Component\Npc\Albert;
use Games\Component\Npc\Alice;
use Games\Component\Items\Munition;
use Games\Component\Items_Equipable\Aka;

class Foret extends Blueprint
{
    private Position $position;

    public function __construct()
    {
        $this->position = new Position(1, 0);
    }

    public function npcs(): array
    {
        $albert = new Albert();
        $alice = new Alice();

        $albert->setPosition(new Position(rand(0, 10), rand(0, 10)));
        $alice->setPosition(new Position(rand(0, 10), rand(0, 10)));

        return [$albert, $alice];
    }

    public function items(): array
    {
        return [new Munition()];
    }

    public function aka(): array
    {
        return [new Aka()];
    }

    public function monsters(): array
    {
        $monsters = [];

        // Ajouter des Zombies
        for ($i = 0; $i < 5; $i++) {
            $zombie = new Zombie();
            $zombie->setPosition(new Position(rand(0, 10), rand(0, 10)));
            $monsters[] = $zombie;
        }

        // Ajouter des Chiens Zombies
        for ($i = 0; $i < 3; $i++) {
            $chien_zombie = new Chien_Zombie();
            $chien_zombie->setPosition(new Position(rand(0, 10), rand(0, 10)));
            $monsters[] = $chien_zombie;
        }

        // Ajouter un Nemesis avec une probabilité de 20%
        if (rand(1, 100) <= 20) {
            $nemesis = new Nemesis();
            $nemesis->setPosition(new Position(rand(0, 10), rand(0, 10)));
            $monsters[] = $nemesis;
        }

        return $monsters;
    }

    public function name(): string
    {
        return "Forêt dense";
    }

    public function description(): string
    {
        return "Vous êtes dans une forêt dense. Les arbres sont hauts et épais, "
            . "avec des feuilles qui forment un toit presque impénétrable au-dessus de vous. "
            . "Le sol est couvert de feuilles mortes et de branches cassées, et l'air est frais "
            . "et humide.";
    }

    public function position(): Position
    {
        return $this->position;
    }
}