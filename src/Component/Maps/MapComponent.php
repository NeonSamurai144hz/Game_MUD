<?php

namespace Games\Component\Maps;

use Jugid\Staurie\Component\Map\Map;

class MapComponent extends Map
{
    final public function defaultConfiguration(): array
    {
        return [
            'directory' => __DIR__ . '/Blueprints', // dossier contenant tes blueprints
            'namespace' => 'Games\Component\Maps\Blueprints',
        ];
    }
}
