<?php

namespace Games\Component\Character\CoreFunctions;

use Jugid\Staurie\Component\Console\AbstractConsoleFunction;

class LookFunction extends AbstractConsoleFunction
{
    public function action(array $args): void
    {
        $this->dispatch('character.look', ['target' => $args[0] ?? null]);
    }

    public function getArgs(): int|array
    {
        return [0, 1]; // Can take 0 or 1 argument
    }

    public function name(): string
    {
        return 'look';
    }

    public function description(): string
    {
        return 'Look around the current area or examine something specific';
    }
}
