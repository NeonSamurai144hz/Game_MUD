<?php

namespace Games\Component\Menu;

use Jugid\Staurie\Component\AbstractComponent;
use Jugid\Staurie\Component\Console\Console;
use Jugid\Staurie\Component\PrettyPrinter\PrettyPrinter;
use LogicException;

class Menu extends AbstractComponent {

    const MENU_OPTIONS_ORDER = ['new_game', 'quit', 'continue'];

    private $menu_options = [];

    final public function name(): string {
        return 'menu';
    }

    final public function getEventName(): array {
        return ['menu.show'];
    }

    final public function require(): array {
        return [Console::class, PrettyPrinter::class];
    }

    final public function initialize(): void {
        foreach(self::MENU_OPTIONS_ORDER as $option) {
            if(!in_array($option, array_keys($this->config['labels']))) {
                throw new LogicException('You MUST set all the labels for you menu labels if you change one');
            }

            $this->menu_options[] = $this->config['labels'][$option];
        }
    }

    final public function defaultConfiguration(): array {
        return [
            'labels'=> [
                'new_game'=> 'Nouvelle partie',
                'continue' => 'Continue',
                'quit'=>'Quit'
            ],
            'text'=>null
        ];
    }

    final protected function action(string $event, array $arguments): void {
        $this->eventToAction($event);
    }

    final protected function show(): void {
        $pp = $this->container->getPrettyPrinter();
        $menu_title = strtoupper($this->container->state()->getGameName() .'\'s menu');

        $pp->writeUnder($menu_title, null, null, true);
        $pp->writeLn('');

        if(null !== $this->config['text']) {
            $pp->writeLn($this->config['text'], null, null, true);
            $pp->writeLn('');
        }

        foreach($this->menu_options as $index=>$option) {
            $pp->writeLn('['.$index.'] '.$option, null, null, true);
        }

        $choice = readline('>> ');

        switch($choice) {
            case '0':
                $this->newgame();
                break;
            case '2':
                $this->continue();
                break;
            case '1':
                $this->container->state()->stop();
                break;
            default:
                $pp->writeLn('Not a valid answer', 'red');
                $this->show();
                break;
        }
    }

    private function continue(): void {
        $pp = $this->container->getPrettyPrinter();
        $pp->writeLn('la sauvegarde nest pas encore mise', null, 'red', true);
        $this->show();
    }

    private function newgame(): void {
        $pp = $this->container->getPrettyPrinter();
        $pp->writeLn("bienvenu dans l'enfer \n", 'green', null, true);

        // Initialize character
        $this->container->dispatcher()->dispatch('character.new');

        // Show introduction
        $this->container->dispatcher()->dispatch('introduction.show');

        // Show initial dialogue with choices
        $this->container->dispatcher()->dispatch('dialogue.show', [
            'lines' => [
                ['Leon', '... Ugh... My head...'],
                ['Narrator', 'You slowly open your eyes in a cold, damp room.'],
                ['Narrator', 'The air is thick with the smell of disinfectant and decay.'],
                ['Leon', 'This doesn\'t feel right... I need to move.']
            ],
            'choices' => [
                [
                    'text' => 'Stand up and look around carefully',
                    'event' => 'character.look',
                    'params' => []
                ],
                [
                    'text' => 'Call out "Hello? Anyone there?"',
                    'event' => 'dialogue.show',
                    'params' => [
                        'lines' => [
                            ['Leon', 'Hello? Anyone there?'],
                            ['Narrator', 'Your voice echoes in the empty room. No response.'],
                            ['Narrator', 'But you hear a distant groaning sound...']
                        ]
                    ]
                ],
                [
                    'text' => 'Stay still and listen to the environment',
                    'event' => 'character.listen',
                    'params' => []
                ],
                [
                    'text' => 'Check your pockets for items',
                    'event' => 'dialogue.show',
                    'params' => [
                        'lines' => [
                            ['Leon', 'Let me check what I have...'],
                            ['Narrator', 'You find your police badge and an empty holster.'],
                            ['Leon', 'Great... no weapon. This keeps getting better.']
                        ]
                    ]
                ]
            ]
        ]);

        $pp->writeLn('');

        // Start the map after dialogue
        $this->container->dispatcher()->dispatch('map.start', [
            'map' => 'Hospital'
        ]);
    }
}