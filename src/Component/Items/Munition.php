<?php

namespace Games\Component\Items;

use Jugid\Staurie\Game\Position\Position;

use Jugid\Staurie\Game\Item;

class Munition extends Item {

  private Position $position;

    public function setPosition(Position $pos): void {
        $this->position = $pos;
    }

    public function position(): Position {
        return $this->position;
    }

    public function name() : string {
        return 'munition';
    }

    public function description(): string
    {
        return 'munition de  pistolet';
    }


    public function statistics(): array
{
    return [
        'amount' => 3
    ];
}






}
