<?php

namespace Games\Component\Character;

use Jugid\Staurie\Component\AbstractComponent;
use Jugid\Staurie\Component\Character\CoreFunctions\EquipFunction;
use Games\Component\Character\CoreFunctions\fightfunction\FightFunction;
use Games\Component\Character\CoreFunctions\MoveFunction;
use Jugid\Staurie\Component\Character\CoreFunctions\MainCharacterFunction;
use Jugid\Staurie\Component\Character\CoreFunctions\SpeakFunction;
use Jugid\Staurie\Component\Character\CoreFunctions\StatsFunction;
use Jugid\Staurie\Component\Character\CoreFunctions\UnequipFunction;
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

        // Fonction principale du personnage
        $console->addFunction(new MainCharacterFunction());

        // Map et fonctions de combat
        if ($this->container->isComponentRegistered(Map::class)) {
            $console->addFunction(new SpeakFunction());

            if ($this->config['fight_enable']) {
                $console->addFunction(new FightFunction());
            }

            $console->addFunction(new MoveFunction());
        }

        // Inventaire
        if ($this->container->isComponentRegistered(Inventory::class)) {
            $console->addFunction(new EquipFunction());
            $console->addFunction(new UnequipFunction());
        }

        // Statistiques / niveau
        if ($this->container->isComponentRegistered(Level::class)) {
            $console->addFunction(new StatsFunction());
        }

        // Initialisation des attributs
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
            default:
                $this->eventToAction($event);
                break;
        }
    }

  final protected function new()
    {
        if (!$this->config['ask_name']) {
            $this->name = $this->config['name'];
        }

        $pp = $this->container->getPrettyPrinter();
        $pp->writeLn('...', 'yellow');
        sleep(1);
        $pp->writeLn('My head... Where am I?', 'yellow');
        sleep(1);
        $pp->writeLn('...I need to find out what happened here.', 'yellow');
        sleep(1);
        $pp->writeLn('Welcome ' . $this->name, 'green');
    }

    final protected function me(): void
    {
        $pp = $this->container->getPrettyPrinter();
        $pp->writeUnder('Details', 'green');

        if ($this->config['character_has_name']) {
            $pp->writeLn('Name : ' . $this->name);
        }

        if ($this->config['character_has_gender']) {
            $pp->writeLn('Gender : ' . $this->gender);
        }

        $this->container->dispatcher()->dispatch('race.view');
        $this->container->dispatcher()->dispatch('tribe.view');
        $this->container->dispatcher()->dispatch('level.view');

        // Equipment
        $pp->writeUnder("\nYour equipment", 'green');
        $header = ['Body part', 'Name', 'Statistics'];
        $lines = [];
        foreach ($this->equipment as $body_part => $equip) {
            $stats = array_map(
                fn($type, $value) => "$type : $value",
                array_keys($equip?->statistics() ?? []),
                array_values($equip?->statistics() ?? [])
            );
            $lines[] = [$body_part, $equip?->name() ?? '---', implode(', ', $stats)];
        }
        $pp->writeTable($header, $lines);

        // Statistics
        $pp->writeUnder("\nYour statistics", 'green');
        $header = ['Attribute', 'Value'];
        $lines = [];
        foreach ($this->statistics->asArray() as $name => $value) {
            $lines[] = [ucfirst($name), $value];
        }
        $pp->writeTable($header, $lines);
    }

    private function speak(string $npc_name): void
    {
        $pp = $this->container->getPrettyPrinter();
        $npc = $this->container->getMap()->getCurrentBlueprint()->getNpc($npc_name);

        if ($npc instanceof Npc) {
            $this->printNpcDialog($npc_name, $npc->speak());
        } else {
            $pp->writeLn('You are probably talking to a ghost', 'red');
        }
    }

    private function equip(string $item_name, string $body_part): void
    {
        $pp = $this->container->getPrettyPrinter();
        $inventory = $this->container->getInventory();
        $item = $inventory->getItem($item_name);

        if (!$item) {
            $pp->writeLn('Item not found', 'red');
            return;
        }

        if (!in_array($body_part, array_keys($this->equipment))) {
            $pp->writeLn('Body part does not exist. Should be in ' . implode(',', array_keys($this->equipment)), 'red');
            return;
        }

        if (!$item instanceof Item_Equippable) {
            $pp->writeLn('This item is not equippable', 'red');
            return;
        }

        if ($body_part !== $item->body_part()) {
            $pp->writeLn("This item cannot be on your $body_part", 'red');
            return;
        }

        if ($this->equipment[$body_part] !== null) {
            $this->unequip($this->equipment[$body_part]->name(), $body_part);
        }

        $this->equipment[$body_part] = clone $item;
        foreach ($item->statistics() as $type => $value) {
            $this->statistics->add($type, $value);
        }

        $inventory->removeItem($item_name);
        $pp->writeLn("Item $item_name is yours !");
    }

    private function unequip(string $item_name, string $body_part): void
    {
        $pp = $this->container->getPrettyPrinter();
        $inventory = $this->container->getInventory();

        if (!in_array($body_part, array_keys($this->equipment))) {
            $pp->writeLn('Body part does not exist. Should be in ' . implode(',', array_keys($this->equipment)), 'red');
            return;
        }

        $item = $this->equipment[$body_part];
        if (!$item || $item->name() !== $item_name) {
            $pp->writeLn('Item not found', 'red');
            return;
        }

        $inventory->addItem(clone $item);
        foreach ($item->statistics() as $type => $value) {
            $this->statistics->sub($type, $value);
        }

        $this->equipment[$body_part] = null;
        $pp->writeLn("This $item_name was not worthy !");
    }

    private function stats(string $type, string $stat): void
    {
        $pp = $this->container->getPrettyPrinter();
        $level = $this->container->getComponent('level');

        if (!isset($this->statistics->asArray()[$stat])) {
            $pp->writeLn("Stat $stat does not exist.", 'red');
            return;
        }

        if ($type === 'add') {
            if ($level->points > 0) {
                $this->statistics->add($stat, 1);
                $level->points -= 1;
                $pp->writeLn("One point added to $stat", 'green');
            } else {
                $pp->writeLn("You don't have enough points", 'red');
            }
        } else {
            $pp->writeLn("You can only use function add", 'red');
        }
    }

    private function fight(string $monster_name): void
    {
        $pp = $this->container->getPrettyPrinter();
        $blueprint = $this->container->getMap()->getCurrentBlueprint();
        $monster = $blueprint->getMonster($monster_name);

        if (!$monster) {
            $pp->writeLn('This monster is not accessible', 'red');
            return;
        }

        $fight_config = $this->config['fight'];
        $player_health = $this->statistics->value($fight_config['health']);
        $player_defense = $this->statistics->value($fight_config['defense']);
        $player_attack = $this->statistics->value($fight_config['attack']);
        $monster_health = $monster->health_points();

        $pp->writeLn("Fight starts against $monster_name !", 'green');
        $round = 1;

        while ($monster_health > 0 && $player_health > 0) {
            $pp->writeUnder("Round $round starts", "green");
            $pp->writeLn("[0] Attack\n[1] Defend\n[2] Escape\n");

            do {
                $fight_choice = readline('What would you do ? ');
            } while (!in_array($fight_choice, ['0', '1', '2']));

            switch ($fight_choice) {
                case '0':
                    $monster_damages = max($monster->attack() - $player_defense, 0);
                    $player_damages = max($player_attack - $monster->defense(), 0);
                    $monster_health -= $player_damages;
                    $player_health -= $monster_damages;
                    $pp->writeLn("$monster_name attacks. You lose $monster_damages", 'red');
                    $pp->writeLn("$monster_name loses $player_damages", 'red');
                    break;
                case '1':
                    $monster_damages = max($monster->attack() - $player_defense * 2, 0);
                    $player_health -= $monster_damages;
                    $pp->writeLn("$monster_name attacks. You lose $monster_damages", 'red');
                    break;
                case '2':
                    $health_diff = $player_health / 2;
                    $this->statistics->sub($fight_config['health'], $health_diff);
                    $pp->writeLn("You escape, losing 50% of your current health ($health_diff).", 'red');
                    return;
            }

            $pp->writeUnder("You : $player_health, $monster_name: $monster_health");
            $round++;
        }

        if ($monster_health <= 0) {
            $pp->writeLn("$monster_name is dead. Get ready for the next one !");
            $blueprint->killMonster($monster_name);
            $level = $this->container->getComponent('level');
            $level->experience += $monster->experience();
            $level->verify();
        } elseif ($player_health <= 0) {
            $pp->writeLn('You are dead. Not ready for that...');
            $action_at_death = $this->config['action_at_death'];
            $map = $this->container->getMap();

            match (true) {
                $action_at_death instanceof Position => $map->teleport($action_at_death),
                $action_at_death === null => exit('...You lose')
            };
        }

        if (!$this->config['health_reset_after_fight']) {
            $health_diff = $this->statistics->value($fight_config['health']) - $player_health;
            $this->statistics->sub($fight_config['health'], $health_diff);
            $pp->writeLn("You lose $health_diff in the fight.", 'red');
        }
    }

    private function printNpcDialog(string $npc_name, string|array $dialog): void
    {
        if (is_string($dialog)) {
            $this->printNpcSingleDial($npc_name, $dialog);
            return;
        }

        foreach ($dialog as $dial) {
            $this->printNpcSingleDial($npc_name, $dial);
        }
    }

    private function printNpcSingleDial(string $npc_name, string $dial): void
    {
        $pp = $this->container->getPrettyPrinter();
        $pp->write($npc_name . ' : ', 'green');
        $pp->writeScroll($dial, 20);
    }

  final public function defaultConfiguration(): array
  {
    return [
      'name' => 'Leon Kennedy',
      'gender' => 'Unknown',
      'ask_name' => 'false',
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
