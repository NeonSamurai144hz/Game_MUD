<?php

namespace Games\Component\Character\CoreFunctions;

use Jugid\Staurie\Component\Console\AbstractConsoleFunction;

class MoveFunction extends AbstractConsoleFunction
{
    public function name(): string
    {
        return 'move';
    }

    public function description(): string
    {
        return 'Move your character on the current map. Usage: move up|down|left|right';
    }

    public function getArgs(): int|array
    {
        return 1; // une direction
    }

    public function action(array $args): void
    {
        $direction = strtolower($args[0]);
        $valid_directions = ['up', 'down', 'left', 'right'];

        if (!in_array($direction, $valid_directions)) {
            $this->getContainer()->getPrettyPrinter()->writeLn(
                "Invalid direction. Choose up, down, left or right.", 'red'
            );
            return;
        }

        $blueprint = $this->getContainer()->getMap()->getCurrentBlueprint();
        $blueprint->move($direction);

        $pos = $blueprint->position();
        $this->getContainer()->getPrettyPrinter()->writeLn(
            "You moved $direction. Current position: ({$pos->x}, {$pos->y})", 'green'
        );
    }
}
