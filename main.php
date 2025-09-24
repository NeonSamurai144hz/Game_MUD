<?php

use Jugid\Staurie\Staurie;
use Jugid\Staurie\Component\Console\Console;
use Jugid\Staurie\Component\PrettyPrinter\PrettyPrinter;
use Jugid\Staurie\Component\Map\Map;
use Games\Component\Menu\Menu;
use Games\Component\Dialogue\DialogueComponent;
use Games\Component\Introduction\Introduction;
use Games\Component\Character\MainCharacter;

require_once __DIR__ . '/vendor/autoload.php';

// Initialisation du moteur Staurie
$staurie = new Staurie('Resident Evil');

// Enregistrement des composants de base
$staurie->register([
    Console::class,
    PrettyPrinter::class,
    Menu::class,
    DialogueComponent::class,
    Introduction::class,
    MainCharacter::class
]);

// Récupération du container pour configurer les composants
$container = $staurie->getContainer();

// Configuration du composant Map
$map = $container->registerComponent(Map::class);
$map->configuration([
    'directory'       => __DIR__ . '/src/Component/Maps/Blueprints',
    'namespace'       => 'Games\Component\Maps\Blueprints',
    'navigation'      => true,
    'map_enable'      => true,
    'compass_enable'  => true
]);


// Lancement du jeu
$staurie->run();
