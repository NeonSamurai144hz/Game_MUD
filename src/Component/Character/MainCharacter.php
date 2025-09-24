<?php

namespace Games\Component\Character;

use Jugid\Staurie\Component\AbstractComponent;
use Jugid\Staurie\Component\Character\CoreFunctions\EquipFunction;
use Games\Component\Character\CoreFunctions\FightFunction;
use Jugid\Staurie\Component\Character\CoreFunctions\MainCharacterFunction;
use Jugid\Staurie\Component\Character\CoreFunctions\MoveFunction;
use Jugid\Staurie\Component\Character\CoreFunctions\SpeakFunction;
use Jugid\Staurie\Component\Character\CoreFunctions\StatsFunction;
use Jugid\Staurie\Component\Character\CoreFunctions\UnequipFunction;
use Games\Component\Character\CoreFunctions\LookFunction; // <-- ajouté
use Jugid\Staurie\Component\Inventory\Inventory;
use Jugid\Staurie\Component\Level\Level;
use Jugid\Staurie\Component\Map\Map;
use Jugid\Staurie\Component\PrettyPrinter\PrettyPrinter;
use Jugid\Staurie\Game\Item_Equippable;
use Jugid\Staurie\Game\Npc;
use Jugid\Staurie\Game\Position\Position;
use LogicException;

class MainCharacter extends AbstractComponent
{
    public Statistics $statistics;
    public string $name;
    public string $gender;
    public array $equipment;

    final public function name(): string
    {
        return 'character';
    }

    final public function getEventName(): array
    {
        $events = ['character.me', 'character.new', 'character.look'];

        if ($this->container->isComponentRegistered(Map::class)) {
            $events[] = 'character.speak';
            $events[] = 'character.fight';
        }

        if ($this->container->isComponentRegistered(Inventory::class)) {
            $events[] = 'character.equip';
            $events[] = 'character.unequip';
        }

        if ($this->container->isComponentRegistered(Level::class)) {
            $events[] = 'character.stats';
        }

        return $events;
    }

    final public function require(): array
    {
        return [PrettyPrinter::class];
    }

    final public function initialize(): void
    {
        $console = $this->container->getConsole();

        $console->addFunction(new MainCharacterFunction());

        if ($this->container->isComponentRegistered(Map::class)) {
            $console->addFunction(new SpeakFunction());
            if ($this->config['fight_enable']) {
                $console->addFunction(new FightFunction());
            }
            $console->addFunction(new LookFunction()); // <-- ajouté pour look
        }

        if ($this->container->isComponentRegistered(Inventory::class)) {
            $console->addFunction(new EquipFunction());
            $console->addFunction(new UnequipFunction());
        }

        if ($this->container->isComponentRegistered(Level::class)) {
            $console->addFunction(new StatsFunction());
        }

        $this->statistics = $this->config['statistics'];
        $this->name = $this->config['name'];
        $this->gender = $this->config['gender'];
        $this->equipment = $this->config['equipment'];
    }

    final protected function action(string $event, array $arguments): void
    {
        switch ($event) {
            case 'character.speak':
                $this->speak($arguments['to']);
                break;
            case 'character.equip':
                $this->equip($arguments['item'], $arguments['body_part']);
                break;
            case 'character.unequip':
                $this->unequip($arguments['item'], $arguments['body_part']);
                break;
            case 'character.stats':
                $this->stats($arguments['type'], $arguments['stat']);
                break;
            case 'character.fight':
                $this->fight($arguments['monster']);
                break;
            case 'character.look':
                $this->lookAround();
                break;
            default:
                $this->eventToAction($event);
        }
    }

    // -----------------------
    // Fonctions existantes
    // -----------------------

    final protected function new(): void { /* inchangé */ }
    final protected function me(): void { /* inchangé */ }
    private function speak(string $npc_name): void { /* inchangé */ }
    private function equip(string $item_name, string $body_part): void { /* inchangé */ }
    private function unequip(string $item_name, string $body_part): void { /* inchangé */ }
    private function stats(string $type, string $stat): void { /* inchangé */ }
    private function fight(string $monster_name): void { /* inchangé */ }
    private function printNpcDialog(string $npc_name, string|array $dialog): void { /* inchangé */ }
    private function printNpcSingleDial(string $npc_name, string $dial): void { /* inchangé */ }

    // ========================
    // Nouvelle fonction look
    // ========================
    private function lookAround(): void
    {
        $map = $this->container->getMap();
        $blueprint = $map->getCurrentBlueprint();
        $pp = $this->container->getPrettyPrinter();

        $pp->writeUnder("You are in: " . $blueprint->name(), 'green');
        $pp->writeLn($blueprint->description());

        foreach ($blueprint->getNpcs() as $npc) {
            $pp->writeLn("NPC here: " . $npc->name());
        }

        foreach ($blueprint->getMonsters() as $monster) {
            $pp->writeLn("Monster here: " . $monster->name());
        }

        foreach ($blueprint->getItems() as $item) {
            $pp->writeLn("Item here: " . $item->name());
        }

        foreach (['north','south','east','west'] as $dir) {
            if ($blueprint->canMove($dir)) {
                $pp->writeLn("You can go $dir");
            }
        }
    }

    final public function defaultConfiguration(): array
    {
        return [
            'name' => 'Leon Kennedy',
            'gender' => 'Unknown',
            'ask_name' => false,
            'character_has_name' => true,
            'statistics' => Statistics::default(),
            'fight_enable' => true,
            'fight' => [
                'health' => 'health',
                'attack' => 'ability',
                'defense' => 'defense',
                'dodge' => 'dodge'
            ],
            'health_reset_after_fight' => true,
            'equipment' => [
                'head' => null,
                'hand' => null,
                'shield' => null,
                'feet' => null,
                'shoulders' => null,
            ],
            'action_at_death' => null
        ];
    }

    final public function hasEnoughStats(string $stat_name, int $value): bool
    {
        if (!$this->statistics->has($stat_name)) {
            throw new LogicException("Stat $stat_name does not exist");
        }
        return $this->statistics->value($stat_name) >= $value;
    }
}
