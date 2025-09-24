<?php
namespace Games\Component\Npc;

use Jugid\Staurie\Game\Npc;
use Jugid\Staurie\Game\Position\Position;

class Alice extends Npc
{
    private ?Position $position;

    public function __construct()
    {
        $this->position = null;
    }

    public function name(): string
    {
        return 'Alice';
    }

    public function description(): string
    {
        return 'une princesse avec des secrets';
    }

    public function level(): int
    {
        return 1;
    }

    public function health_points(): int
    {
        return 50;
    }

    public function defense(): int
    {
        return 0;
    }

    public function skills(): array
    {
        return [
            'attack' => 5,
            'dodge' => 3
        ];
    }

    public function speak(): string|array
    {
        return "Hi hi hi, je suis la princesse Alice.";
    }

    public function setPosition(Position $position): void
    {
        $this->position = $position;
    }

    public function position(): ?Position
    {
        return $this->position;
    }
}
