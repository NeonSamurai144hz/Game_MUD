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
use Games\Component\Character\CoreFunctions\LookFunction;
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
        $events = ['character.me', 'character.new'];

        if ($this->container->isComponentRegistered(Map::class)) {
            $events[] = 'character.speak';
            $events[] = 'character.fight';
            $events[] = 'character.move';   // added
            $events[] = 'character.listen'; // added
            $events[] = 'character.look';   // added
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

    protected function action(string $event, array $arguments): void
    {
        switch ($event) {
            case 'character.look':
                $this->onCharacterLook($arguments);
                break;
            case 'character.speak':
                $this->speak(
                    $arguments['to'] ?? 'Unknown',
                    $arguments['line'] ?? '...'
                );
                break;
            case 'character.move':
                $this->onCharacterMove($arguments);
                break;
            case 'character.listen':
                $this->onCharacterListen($arguments);
                break;
        }
    }




    final protected function new(): void { /* inchangé */ }
    final protected function me(): void { /* inchangé */ }
    private function speak(string $npc_name): void { /* inchangé */ }
    private function equip(string $item_name, string $body_part): void { /* inchangé */ }
    private function unequip(string $item_name, string $body_part): void { /* inchangé */ }
    private function stats(string $type, string $stat): void { /* inchangé */ }
    private function fight(string $monster_name): void { /* inchangé */ }
    private function printNpcDialog(string $npc_name, string|array $dialog): void { /* inchangé */ }
    private function printNpcSingleDial(string $npc_name, string $dial): void { /* inchangé */ }


   private function lookAround(): void
{
    $map = $this->container->getMap();
    $blueprint = $map->getCurrentBlueprint();
    $pp = $this->container->getPrettyPrinter();

    $pp->writeUnder("You are in: " . $blueprint->name(), 'green');
    $pp->writeLn($blueprint->description());

    // PNJs
    foreach ($blueprint->getNpcs() as $npc) {
        $pp->writeLn("NPC here: " . $npc->name());
    }

    // Monstres
    foreach ($blueprint->getMonsters() as $monster) {
        $pp->writeLn("Monster here: " . $monster->name());
    }

    // Items
    foreach ($blueprint->getItems() as $item) {
        $pp->writeLn("Item here: " . $item->name());
    }

    // Directions possibles
    $directions = [
        'north' => ['x' => 0, 'y' => 1],
        'south' => ['x' => 0, 'y' => -1],
        'east'  => ['x' => 1, 'y' => 0],
        'west'  => ['x' => -1, 'y' => 0]
    ];

    foreach ($directions as $dir => $delta) {
        $pos = clone $map->current_position;
        $pos->x += $delta['x'];
        $pos->y += $delta['y'];

        if ($map->getBlueprint($pos)) {
            $pp->writeLn("You can go $dir");
        }
    }
}
    private function onCharacterMove(array $args = []): void
    {
        $pp = $this->container->getPrettyPrinter();

        // Ask a direction or accept a preset from payload
        $direction = $args['direction'] ?? null;
        if (!$direction) {
            $pp->writeLn("Which direction? (north, south, east, west)", null, null, true);
            $direction = trim((string)readline('>> '));
        }

        if ($direction === '') {
            $pp->writeLn("You stay where you are.", null, null, true);
            return;
        }

        // Try to dispatch a map movement event (map component should handle it)
        $this->container->dispatcher()->dispatch('map.move', ['direction' => $direction]);

        // Fallback message (map.move handler may give better output)
        $pp->writeLn("You move {$direction}.", null, null, true);
    }

    private function onCharacterListen(array $args = []): void
    {
        $pp = $this->container->getPrettyPrinter();
        // simple atmospheric feedback; other components can react to 'world.noise'
        $pp->writeScroll("You hold still and listen... a distant groan comes from the east.", 8, true);
        $this->container->dispatcher()->dispatch('world.noise', ['type' => 'groan', 'direction' => 'east']);
    }

    private function onCharacterLook(array $args = []): void
    {
        $pp = $this->container->getPrettyPrinter();
        $map = $this->container->getMap();

        // Try to read current blueprint description if available
        $descr = null;
        if ($map && method_exists($map, 'getCurrentBlueprint')) {
            $bp = $map->getCurrentBlueprint();
            if ($bp && method_exists($bp, 'description')) {
                $descr = $bp->description();
            }
        }

        if ($descr) {
            $pp->writeScroll("You look around: " . $descr, 8, true);
        } else {
            // fallback descriptive text
            $pp->writeScroll("You look around: a cracked window, an old cabinet, and a locked heavy door.", 8, true);
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
