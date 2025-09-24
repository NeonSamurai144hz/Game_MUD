<?php

namespace Games\Component\Character\CoreFunctions;

use Jugid\Staurie\Component\Console\AbstractConsoleFunction;

class FightFunction extends AbstractConsoleFunction
{
    public function action(array $args): void
    {
        $target = $args[0] ?? '';
        $this->dispatch('character.fight', ['target' => $target]);
    }

    public function getArgs(): int|array
    {
        return 1; // Requires 1 argument
    }

    public function name(): string
    {
        return 'fight';
    }

    public function description(): string
    {
        return 'Attack a monster or enemy';
    }
}
