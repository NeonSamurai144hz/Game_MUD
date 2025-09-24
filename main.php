<?php

require_once __DIR__.'/vendor/autoload.php';

use Jugid\Staurie\Staurie;
use Jugid\Staurie\Component\Console\Console;
use Jugid\Staurie\Component\PrettyPrinter\PrettyPrinter;
use Jugid\Staurie\Component\Inventory\Inventory;
use Jugid\Staurie\Component\Map\Map;

// Import your custom components
use Games\Component\Menu\Menu;
use Games\Component\Introduction\Introduction;
use Games\Component\Character\MainCharacter;
use Games\Component\Dialogue\DialogueComponent;
use Games\Component\ActionSystem\ActionSystemComponent;

// Initialize the game
$staurie = new Staurie('RESIDENT EVIL');

// Get the container for configuration
$container = $staurie->getContainer();

// Configure core components first
$staurie->register([
    Console::class,
    PrettyPrinter::class
]);

// Configure Menu
$menu = $container->registerComponent(Menu::class);
$menu->configuration([
    'text' => "RESIDENT EVIL'S MENU\n--------------------",
    'labels' => [
        'new_game' => 'Nouvelle partie',
        'quit' => 'Quit',
        'continue' => 'Continue',
    ]
]);

// Configure Introduction
$introduction = $container->registerComponent(Introduction::class);
$introduction->configuration([
    'title' => 'RESIDENT EVIL',
    'text' => [
        "Welcome player to Resident Evil!",
        "In this game, you will navigate through a world filled with danger.",
        "You are Leon S. Kennedy, a rookie cop on his first day of duty in Raccoon City.",
        "The city is overrun by zombies and other terrifying creatures.",
        "Your mission is to survive and uncover the truth behind the outbreak.",
        "Your mission begins now..."
    ],
    'scrolling' => true,
    'scrolling_speed' => 3
]);

// Configure Character
$character = $container->registerComponent(MainCharacter::class);
$character->configuration([
    'name' => 'Leon S. Kennedy',
    'gender' => 'Male',
    'fight_enable' => true
]);

// Configure Inventory
$inventory = $container->registerComponent(Inventory::class);
$inventory->configuration([
    'inventory_size' => 8, // Resident Evil style limited inventory
    'stackable' => false   // Each item takes one slot
]);

// Configure Map
$map = $container->registerComponent(Map::class);
$map->configuration([
    'directory' => __DIR__ . '/src/Component/Maps/Blueprints',
    'namespace' => 'Games\\Component\\Maps\\Blueprints',
    'navigation' => true,
    'map_enable' => true,
    'compass_enable' => true,
    'x_start' => 0,
    'y_start' => 0
]);

// Register custom components
$dialogue = $container->registerComponent(DialogueComponent::class);
$actionSystem = $container->registerComponent(ActionSystemComponent::class);

try {
    // Start the game
    $staurie->run();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: \n" . $e->getTraceAsString() . "\n";
    exit(1);
}
