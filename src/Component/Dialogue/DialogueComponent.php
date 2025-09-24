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
        return ['dialogue.show'];
    }

    // 🔑 REQUIRED by Initializable
    public function initialize(): void
    {
        // No setup needed yet, but method must exist
    }

    protected function action(string $event, array $arguments): void
    {
        switch ($event) {
            case 'dialogue.show':
                $this->showDialogue($arguments);
                break;
        }
    }

    private function showDialogue(array $arguments): void
    {
        $pp = $this->container->getPrettyPrinter();

        if (!isset($arguments['lines'])) {
            $pp->writeLn("... (silence) ...", null, null, true);
            return;
        }

        foreach ($arguments['lines'] as $line) {
            [$speaker, $text] = $line;
            $pp->writeLn($speaker . ": " . $text, null, null, true);
            readline(">> "); // pause for player input before next line
        }
    }

    // 🔑 REQUIRED by Configurable
    public function defaultConfiguration(): array
    {
        return [];
    }
}
