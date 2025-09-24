<?php

namespace Games\Component\Dialogue;

use Jugid\Staurie\Component\AbstractComponent;
use Jugid\Staurie\Component\PrettyPrinter\PrettyPrinter;

class DialogueComponent extends AbstractComponent
{
    public function name(): string
    {
        return 'dialogue';
    }

    public function require(): array
    {
        return [PrettyPrinter::class];
    }

    public function getEventName(): array
    {
        return ['dialogue.show', 'dialogue.start', 'dialogue.choice'];
    }

    public function initialize(): void
    {
        // Component is ready, no additional setup needed
    }

    protected function action(string $event, array $arguments): void
    {
        switch ($event) {
            case 'dialogue.show':
                $this->showDialogue($arguments);
                break;
            case 'dialogue.start':
                $this->startDialogue($arguments);
                break;
            case 'dialogue.choice':
                $this->handleChoice($arguments);
                break;
        }
    }

    private function showDialogue(array $arguments): void
    {
        $pp = $this->container->getPrettyPrinter();

        // Display dialogue lines
        if (isset($arguments['lines']) && is_array($arguments['lines'])) {
            foreach ($arguments['lines'] as $line) {
                if (is_array($line) && count($line) >= 2) {
                    [$speaker, $text] = $line;
                    $color = $this->getSpeakerColor($speaker);
                    $pp->writeLn($speaker . ": " . $text, $color, null, true);
                    readline(">> "); // Wait for player to continue
                }
            }
        }

        // Show choices if available
        if (isset($arguments['choices']) && is_array($arguments['choices'])) {
            $this->showChoices($arguments['choices']);
        }
    }

    private function startDialogue(array $arguments): void
    {
        $pp = $this->container->getPrettyPrinter();
        $character = $arguments['character'] ?? 'Unknown';

        $pp->writeLn("--- " . $character . " wakes up ---", 'yellow', null, true);

        // Continue with the main game flow
        $this->container->dispatcher()->dispatch('map.start', ['map' => 'Hospital']);
    }

    private function showChoices(array $choices): void
    {
        $pp = $this->container->getPrettyPrinter();

        $pp->writeLn("", null, null, true);
        $pp->writeLn("What do you want to do?", 'cyan', null, true);

        foreach ($choices as $index => $choice) {
            $pp->writeLn("[" . ($index + 1) . "] " . $choice['text'], 'white', null, true);
        }

        $pp->writeLn("[0] Do nothing", 'gray', null, true);

        $input = readline("Choose an option >> ");
        $this->processChoice($input, $choices);
    }

    private function processChoice(string $input, array $choices): void
    {
        $pp = $this->container->getPrettyPrinter();
        $choiceIndex = intval($input) - 1;

        if ($input === '0') {
            $pp->writeLn("Leon decides to wait and assess the situation...", 'gray', null, true);
            return;
        }

        if ($choiceIndex >= 0 && $choiceIndex < count($choices)) {
            $choice = $choices[$choiceIndex];
            $pp->writeLn("Leon: " . $choice['text'], 'green', null, true);

            // Dispatch the associated event
            if (isset($choice['event'])) {
                $params = $choice['params'] ?? [];
                $this->container->dispatcher()->dispatch($choice['event'], $params);
            }

            // Execute any callback
            if (isset($choice['callback']) && is_callable($choice['callback'])) {
                $choice['callback']();
            }
        } else {
            $pp->writeLn("Invalid choice. Try again.", 'red', null, true);
            $this->showChoices($choices);
        }
    }

    private function getSpeakerColor(string $speaker): string
    {
        return match (strtolower($speaker)) {
            'leon' => 'green',
            'narrator' => 'yellow',
            'claire' => 'magenta',
            'zombie' => 'red',
            'system' => 'cyan',
            default => 'white'
        };
    }

    public function defaultConfiguration(): array
    {
        return [
            'auto_continue' => false,
            'show_speaker_colors' => true
        ];
    }
}