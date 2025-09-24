<?php

namespace Games\Component\Maps;

use Jugid\Staurie\Component\Map\Map as BaseMap;
use Jugid\Staurie\Game\Position\Position;
use Games\Component\Maps\Blueprints\Hopital;

class MyMap extends BaseMap {
    final public function getBlueprint(Position $position): ?\Jugid\Staurie\Component\Map\Blueprint {
        $bp = parent::getBlueprint($position);
        if ($bp === null) {
            // Si aucune blueprint n’existe ici, on renvoie un Hopital
            $hopital = new Hopital();
            $hopital->setPosition($position);
            return $hopital;
        }
        return $bp;
    }
}

?>
