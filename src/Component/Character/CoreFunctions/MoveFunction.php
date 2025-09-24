<?php

namespace Games\Component\Character\CoreFunctions;

use Jugid\Staurie\Component\Console\AbstractConsoleFunction;

class MoveFunction extends AbstractConsoleFunction
{
    public function action(array $args): void
    {
        $direction = $args[0] ?? null;
        $this->dispatch('character.move', ['direction' => $direction]);
    }

    public function getArgs(): int|array
    {
        return [0, 1]; // Can take 0 or 1 argument
    }

    public function name(): string
    {
        return 'move';
    }

    public function description(): string
    {
        return 'Move in a direction (north, south, east, west) or ask for directions';
    }
}