<?php

namespace Jugid\Staurie\Example\Items;

use Jugid\Staurie\Game\Item;

class Munition extends Item {

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
