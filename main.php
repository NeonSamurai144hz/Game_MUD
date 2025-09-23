<?php

use Jugid\Staurie\Component\Console\Console;
use Games\Component\Menu\Menu;
use Jugid\Staurie\Component\PrettyPrinter\PrettyPrinter;
use Games\Component\Character\MainCharacter;
use Games\Component\Introduction\Introduction; 
use Jugid\Staurie\Staurie;

require_once __DIR__ . '/vendor/autoload.php';

// Création du moteur de jeu
$staurie = new Staurie('Resident Evil');

// Enregistrement des composants
$staurie->register([
    Console::class,
    PrettyPrinter::class,
    Menu::class,
    Introduction::class, // <-- ajouter ici
    MainCharacter::class
]);

// Lancement du jeu
$staurie->run();
