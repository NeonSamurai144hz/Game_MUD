<?php

namespace Games\Component\Maps;

use Jugid\Staurie\Component\Map\Map as BaseMap;
use Jugid\Staurie\Game\Position\Position;
use Games\Component\Maps\Blueprints\Hopital;

class InfiniteMap extends BaseMap
{
    final public function getBlueprint(Position $position)
    {
        // Toujours renvoyer un Hopital pour n’importe quelle position
        $hopital = new Hopital();
        $hopital->setPosition($position);
        return $hopital;
    }
}
