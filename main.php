<?php

use Games\Component\Dialogue\DialogueComponent;
use Jugid\Staurie\Component\Console\Console;
use Games\Component\Menu\Menu;
use Jugid\Staurie\Component\PrettyPrinter\PrettyPrinter;
use Games\Component\Character\MainCharacter;
use Games\Component\Introduction\Introduction;
use Jugid\Staurie\Staurie;
use Jugid\Staurie\Component\Map\Map;
use Games\Component\Maps\MapComponent;

require_once __DIR__ . '/vendor/autoload.php';

// Création du moteur de jeu
$staurie = new Staurie('Resident Evil');

// Enregistrement des composants
$staurie->register([
  Console::class,
  PrettyPrinter::class,
  MapComponent::class,   // ton Map personnalisé
  Menu::class,
  DialogueComponent::class,

  Introduction::class,

  MainCharacter::class
]);

$staurie->run();


// Lancement du jeu
$staurie->run();
