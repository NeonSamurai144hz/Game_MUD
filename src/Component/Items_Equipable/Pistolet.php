<?php

namespace Jugid\Staurie\Example\Items;

use Jugid\Staurie\Game\Item_Equippable;

class Pistolet extends Item_Equippable {

    public function name() : string {
        return 'Pistolet';
    }

    public function description(): string
    {
        return 'un simple pistolet';
    }


    public function statistics(): array
    {
        return [
            'attack'=> 3
        ];
    }

    public function body_part(): string
{
    return 'hand';
}

}
