<?php

namespace Games\Component\Introduction;

use Jugid\Staurie\Component\AbstractComponent;
use Jugid\Staurie\Component\PrettyPrinter\PrettyPrinter;

class Introduction extends AbstractComponent
{
    public function name(): string
    {
        return 'introduction';
    }

    final public function require(): array
    {
        return [PrettyPrinter::class];
    }

    public function initialize(): void {}

    public function getEventName(): array
    {
        return ['introduction.show'];
    }

    protected function action(string $event, array $arguments): void
    {
        switch ($event) {
            case 'introduction.show':
                $this->show();
                break;
        }
    }

    private function show()
    {
        $text = $this->config['text'];
        $scrolling = $this->config['scrolling'];
        $scrolling_speed = $this->config['scrolling_speed'];

        $pp = $this->container->getPrettyPrinter();

        $pp->writeUnder(strtoupper($this->config['title']), null, null, true);
        $this->printIntroduction($text, $scrolling, $scrolling_speed, $pp);

        // ---- NEW: trigger wake-up/dialogue right after the cinematic intro ----
        // dispatch dialogue.start with the character name (Leon)
        $this->container->dispatcher()->dispatch('dialogue.start', ['character' => 'Leon']);
    }

    private function printIntroduction(string|array $text, bool $scrolling, int $scrolling_speed, PrettyPrinter $pp)
    {
        if (is_array($text)) {
            foreach ($text as $line) {
                $this->printIntroduction($line, $scrolling, $scrolling_speed, $pp);
            }
            return;
        }

        if (is_string($text)) {
            if ($scrolling) {
                $pp->writeScroll($text, $scrolling_speed, true);
            } else {
                $pp->writeLn($text, null, null, true);
            }
        }
    }

    public function defaultConfiguration(): array
    {
        return [
            'title' => $this->container->state()->getGameName(),
            'text' => [
                'Welcome player to Resident Evil!',
                'In this game, you will navigate through a world filled with danger.',
                'You are Leon S. Kennedy, a rookie cop on his first day of duty in Raccoon City.',
                'The city is overrun by zombies and other terrifying creatures.',
                'Your mission is to survive and uncover the truth behind the outbreak.',
                'Your mission begins now...'
            ],
            'scrolling' => true,
            'scrolling_speed' => 5
        ];
    }
}
