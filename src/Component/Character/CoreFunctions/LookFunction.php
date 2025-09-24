<?php

namespace Games\Component\Character\CoreFunctions;

use Jugid\Staurie\Component\Console\ConsoleFunction;
use Jugid\Staurie\Component\Console\Console;

class LookFunction extends ConsoleFunction
{
    // Nom de la commande
    public function name(): string
    {
        return 'look';
    }

    // Description visible dans le menu du console
    public function description(): string
    {
        return 'Look around your current location (see NPCs, monsters, items, directions)';
    }

    // Exécution de la commande
    public function execute(Console $console, array $args = []): void
    {
        // On déclenche l'événement 'character.look'
        $console->dispatcher()->dispatch('character.look');
    }
}
