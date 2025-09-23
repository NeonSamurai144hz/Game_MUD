<?php

namespace Jugid\Staurie\Example\Items;

use Jugid\Staurie\Game\Item_Equippable;

class Aka extends Item_Equippable {

    public function name() : string {
        return 'Aka';
    }

    public function description(): string
    {
        return 'un Aka';
    }


    public function statistics(): array
    {
        return [
            'attack'=> 5
        ];
    }

    public function body_part(): string
{
    return 'hand';
}

}
