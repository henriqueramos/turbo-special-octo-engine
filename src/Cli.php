<?php

declare (strict_types = 1);

namespace RamosHenrique\TurboSpecialOctoEngine;

use Exception;
use InvalidArgumentException;

class Cli {
    protected $options;

    protected const NULLABLE_BG = '';
    protected const BLACK_BG = '40';
    protected const RED_BG = '41';
    protected const WHITE_BG = '47';

    protected const BLACK_FG = '1;30';
    protected const WHITE_FG = '0;37';

    // ANSI Code Escapes
    protected const SPACE_CODE = "\40";
    protected const TAB_CODE = "\t";

    protected const AVAILABLE_TITLES = [
        0 => '',
        1 => 'Choose an option!',
    ];

    protected const USAGE_TEXT = self::SPACE_CODE . '--file [VALUE] ' . self::TAB_CODE . ' Will search within this file' . PHP_EOL . self::SPACE_CODE . '--help ' . self::TAB_CODE . self::TAB_CODE . ' Display usage instructions' . PHP_EOL;

    public function __construct()
    {
        $this->options = getopt(
            'hf:t:',
            [
                'file:',
                'text:',
                'help',
                'usage',
            ]
        );
    }

    public function run(): void
    {
        try {
            if (!$this->options) {
                throw new InvalidArgumentException(self::USAGE_TEXT, 1);
            }

            if (isset($this->options['help']) || isset($this->options['usage'])) {
                $this->usage();

                exit(0);
            }

            $this->searchElement();
        } catch (Exception $e) {
            $this->baseBlock(
                $e->getMessage(),
                self::AVAILABLE_TITLES[$e->getCode()] ?? '',
                self::WHITE_FG,
                self::RED_BG
            );

            exit(1);
        }
    }

    protected function format(
        string $text = '',
        string $foregroundColor = self::WHITE_FG,
        string $backgroundColor = self::NULLABLE_BG
    ): string {
        return "\e[{$foregroundColor};{$backgroundColor}m{$text}\e[0m";
    }

    protected function openBlock(
        string $foregroundColor = self::WHITE_FG,
        string $backgroundColor = self::NULLABLE_BG
    ): string {
        return "\e[{$foregroundColor};{$backgroundColor}m";
    }

    protected function closeBlock(): string
    {
        return "\e[0m";
    }

    protected function baseBlock(
        string $body,
        ?string $title,
        string $foregroundColor = self::WHITE_FG,
        string $backgroundColor = self::NULLABLE_BG
    ): self {
        echo $this->openBlock($foregroundColor, $backgroundColor) . PHP_EOL . ' ' . PHP_EOL;
        if ($title) {
            echo self::SPACE_CODE . $title . PHP_EOL;
        }
        echo $body . PHP_EOL;
        echo $this->closeBlock();

        return $this;
    }

    protected function usage(): self
    {
        $this->baseBlock(
            self::USAGE_TEXT,
            'Usage instructions',
            self::BLACK_FG,
            self::WHITE_BG
        );

        return $this;
    }

    protected function searchElement(): self
    {
        $file = $this->options['file'];

        if (!file_exists($file)) {
            throw new InvalidArgumentException(self::SPACE_CODE . 'Invalid Path. File should exists!' . PHP_EOL);
        }

        $fileContents = file_get_contents($file);

        preg_match_all('/\[([^\[\]]|(?R))*\]/s', $fileContents, $occurrencesArray, PREG_SET_ORDER);

        $occurrences = [];

        foreach ($occurrencesArray as $occurrence) {
            $text = preg_replace('/(\[|\])/', '', $occurrence[0]);
            $occurrences[] = self::SPACE_CODE . $text;
        }

        $this->baseBlock(
            (string) implode(PHP_EOL, $occurrences) . PHP_EOL,
            'Discovered words between brackets',
            self::BLACK_FG
        );

        return $this;
    }
}
