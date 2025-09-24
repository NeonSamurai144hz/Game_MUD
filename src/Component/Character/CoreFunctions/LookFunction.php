<?php

namespace Games\Component\Character\CoreFunctions;

use Jugid\Staurie\Component\Console\AbstractConsoleFunction;

class LookFunction extends AbstractConsoleFunction
{
    public function name(): string
    {
        return 'look';
    }

    public function description(): string
    {
        return 'Look around your current location (see NPCs, monsters, items, directions)';
    }

    public function getArgs(): int|array
    {
        return 0; // aucun argument
    }

    public function action(array $args): void
    {
        $this->getContainer()->dispatcher()->dispatch('character.look');
    }
}
