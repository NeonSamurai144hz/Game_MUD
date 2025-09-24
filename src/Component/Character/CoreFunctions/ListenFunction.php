<?php

namespace Games\Component\Character\CoreFunctions;

use Jugid\Staurie\Component\Console\AbstractConsoleFunction;

class ListenFunction extends AbstractConsoleFunction
{
    public function action(array $args): void
    {
        $this->dispatch('character.listen', []);
    }

    public function getArgs(): int|array
    {
        return 0; // No arguments needed
    }

    public function name(): string
    {
        return 'listen';
    }

    public function description(): string
    {
        return 'Listen carefully to your surroundings';
    }
}