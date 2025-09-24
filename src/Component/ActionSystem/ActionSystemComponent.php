<?php

namespace Games\Component\ActionSystem;

use Jugid\Staurie\Component\AbstractComponent;
use Jugid\Staurie\Component\Console\AbstractConsoleFunction;
use Jugid\Staurie\Component\PrettyPrinter\PrettyPrinter;

class ActionSystemComponent extends AbstractComponent
{
    public function name(): string
    {
        return 'ActionSystem';
    }

    public function getEventName(): array
    {
        return [
            'action.examine',
            'action.search',
            'action.interact',
            'action.use',
            'action.choice'
        ];
    }

    public function require(): array
    {
        return [PrettyPrinter::class];
    }

    public function initialize(): void
    {
        $console = $this->container->getConsole();
        $console->addFunction(new ExamineFunction());
        $console->addFunction(new SearchFunction());
        $console->addFunction(new InteractFunction());
        $console->addFunction(new UseItemFunction());
        $console->addFunction(new ChoiceFunction());
    }

    protected function action(string $event, array $arguments): void
    {
        switch ($event) {
            case 'action.examine':
                $this->handleExamine($arguments);
                break;
            case 'action.search':
                $this->handleSearch($arguments);
                break;
            case 'action.interact':
                $this->handleInteract($arguments);
                break;
            case 'action.use':
                $this->handleUse($arguments);
                break;
            case 'action.choice':
                $this->handleChoice($arguments);
                break;
        }
    }

    private function handleExamine(array $arguments): void
    {
        $pp = $this->container->getPrettyPrinter();
        $target = $arguments['target'] ?? $arguments[0] ?? '';

        if (empty($target)) {
            $pp->writeLn("Examine what? Be more specific.", 'yellow', null, true);
            return;
        }

        // Resident Evil specific examinations
        $examinations = [
            'room' => [
                "Leon examines the room carefully.",
                "The hospital room shows signs of a struggle. Medical charts are scattered on the floor.",
                "There's dried blood on the examination table.",
                "A medical cabinet in the corner might contain useful supplies."
            ],
            'door' => [
                "Leon approaches the door.",
                "It's a heavy metal door with a small window. The glass is reinforced.",
                "You can hear distant sounds from the corridor beyond.",
                "The door handle turns easily - it's unlocked."
            ],
            'window' => [
                "Leon looks at the boarded window.",
                "Wooden planks have been hastily nailed over the window from the outside.",
                "Through the gaps, you can see it's nighttime.",
                "Strange shadows move in the darkness beyond."
            ],
            'cabinet' => [
                "Leon examines the medical cabinet.",
                "The glass door is slightly ajar. Some supplies might still be inside.",
                "You notice the lock has been broken."
            ],
            'bed' => [
                "Leon looks at the hospital bed.",
                "The sheets are stained with something dark.",
                "A clipboard hangs at the foot of the bed with patient information."
            ]
        ];

        $response = $examinations[$target] ?? [
            "Leon doesn't see anything special about the " . $target . ".",
            "Maybe you should try examining something else."
        ];

        foreach ($response as $line) {
            $pp->writeLn($line, 'cyan', null, true);
            if (count($response) > 1) {
                readline(">> "); // Pause between lines
            }
        }
    }

    private function handleSearch(array $arguments): void
    {
        $pp = $this->container->getPrettyPrinter();
        $location = $arguments['location'] ?? $arguments[0] ?? 'room';

        $pp->writeLn("Leon searches the " . $location . "...", 'yellow', null, true);

        // Simulate search with chance of finding something
        $searchResults = [
            'cabinet' => [
                'items' => ['First Aid Spray', 'Handgun Ammo'],
                'chance' => 80,
                'description' => "Behind some empty medicine bottles, you find some useful supplies."
            ],
            'bed' => [
                'items' => ['Hospital Key Card'],
                'chance' => 60,
                'description' => "Under the pillow, you discover a key card dropped by someone in a hurry."
            ],
            'room' => [
                'items' => ['Herb', 'Flashlight Battery'],
                'chance' => 40,
                'description' => "In the corner behind some equipment, you spot something useful."
            ]
        ];

        $result = $searchResults[$location] ?? $searchResults['room'];

        if (rand(1, 100) <= $result['chance']) {
            $pp->writeLn($result['description'], 'green', null, true);
            $item = $result['items'][array_rand($result['items'])];
            $pp->writeLn("Found: " . $item, 'white', null, true);

            // Here you could add the item to inventory
            $this->container->dispatcher()->dispatch('inventory.add', ['item' => $item]);
        } else {
            $pp->writeLn("Leon searches thoroughly but finds nothing useful.", 'gray', null, true);
        }
    }

    private function handleInteract(array $arguments): void
    {
        $pp = $this->container->getPrettyPrinter();
        $target = $arguments['target'] ?? $arguments[0] ?? '';

        if (empty($target)) {
            $pp->writeLn("Interact with what?", 'yellow', null, true);
            return;
        }

        $interactions = [
            'door' => function() use ($pp) {
                $pp->writeLn("Leon tries the door handle...", 'cyan', null, true);
                $pp->writeLn("The door opens with a creak. A dark corridor stretches beyond.", 'green', null, true);

                // Trigger choice for player
                $this->container->dispatcher()->dispatch('dialogue.show', [
                    'choices' => [
                        ['text' => 'Step into the corridor', 'event' => 'character.move', 'params' => ['direction' => 'north']],
                        ['text' => 'Listen for sounds first', 'event' => 'character.listen'],
                        ['text' => 'Stay in the room', 'event' => 'action.examine', 'params' => ['target' => 'room']]
                    ]
                ]);
            },
            'cabinet' => function() use ($pp) {
                $pp->writeLn("Leon opens the medical cabinet...", 'cyan', null, true);
                $this->container->dispatcher()->dispatch('action.search', ['location' => 'cabinet']);
            },
            'bed' => function() use ($pp) {
                $pp->writeLn("Leon approaches the hospital bed...", 'cyan', null, true);
                $this->container->dispatcher()->dispatch('action.search', ['location' => 'bed']);
            }
        ];

        if (isset($interactions[$target])) {
            $interactions[$target]();
        } else {
            $pp->writeLn("Leon can't interact with that.", 'gray', null, true);
        }
    }

    private function handleUse(array $arguments): void
    {
        $pp = $this->container->getPrettyPrinter();
        $item = $arguments['item'] ?? $arguments[0] ?? '';

        if (empty($item)) {
            $pp->writeLn("Use what item?", 'yellow', null, true);
            return;
        }

        $pp->writeLn("Leon uses the " . $item . ".", 'green', null, true);
        // Add specific use logic here
    }

    private function handleChoice(array $arguments): void
    {
        $pp = $this->container->getPrettyPrinter();
        $choices = $arguments['choices'] ?? [];

        if (empty($choices)) {
            $pp->writeLn("No choices available.", 'gray', null, true);
            return;
        }

        $pp->writeLn("What should Leon do?", 'yellow', null, true);

        foreach ($choices as $index => $choice) {
            $pp->writeLn("[" . ($index + 1) . "] " . $choice['text'], 'white', null, true);
        }

        $input = readline("Choose >> ");
        $choiceIndex = intval($input) - 1;

        if ($choiceIndex >= 0 && $choiceIndex < count($choices)) {
            $choice = $choices[$choiceIndex];
            $this->container->dispatcher()->dispatch($choice['event'], $choice['params'] ?? []);
        } else {
            $pp->writeLn("Invalid choice.", 'red', null, true);
            $this->handleChoice($arguments);
        }
    }

    public function defaultConfiguration(): array
    {
        return [];
    }
}

// Console Functions
class ExamineFunction extends AbstractConsoleFunction
{
    public function action(array $args): void
    {
        $this->dispatch('action.examine', $args);
    }

    public function getArgs(): int|array
    {
        return 1;
    }

    public function name(): string
    {
        return 'examine';
    }

    public function description(): string
    {
        return 'Examine something in detail';
    }
}

class SearchFunction extends AbstractConsoleFunction
{
    public function action(array $args): void
    {
        $this->dispatch('action.search', $args);
    }

    public function getArgs(): int|array
    {
        return [0, 1];
    }

    public function name(): string
    {
        return 'search';
    }

    public function description(): string
    {
        return 'Search an area for items';
    }
}

class InteractFunction extends AbstractConsoleFunction
{
    public function action(array $args): void
    {
        $this->dispatch('action.interact', $args);
    }

    public function getArgs(): int|array
    {
        return 1;
    }

    public function name(): string
    {
        return 'interact';
    }

    public function description(): string
    {
        return 'Interact with objects in the environment';
    }
}

class UseItemFunction extends AbstractConsoleFunction
{
    public function action(array $args): void
    {
        $this->dispatch('action.use', $args);
    }

    public function getArgs(): int|array
    {
        return 1;
    }

    public function name(): string
    {
        return 'use';
    }

    public function description(): string
    {
        return 'Use an item from your inventory';
    }
}

class ChoiceFunction extends AbstractConsoleFunction
{
    public function action(array $args): void
    {
        $this->dispatch('action.choice', $args);
    }

    public function getArgs(): int|array
    {
        return [0, 1];
    }

    public function name(): string
    {
        return 'choice';
    }

    public function description(): string
    {
        return 'Make a choice when presented with options';
    }
}