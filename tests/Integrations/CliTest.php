<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CliTest extends TestCase
{
    protected $cliPath;
    protected $command;

    protected function setUp(): void
    {
        $this->cliPath = dirname(__DIR__, 2);
        $this->command = 'php -f ' . $this->cliPath . '/cli.php --';

        parent::setUp();
    }

    public function testUsageCommand(): void
    {
        $output = `$this->command --usage 2>&1`;
        $this->assertMatchesRegularExpression('/Usage instructions/m',$output, 'Could not find the usage instructions');
    }

    public function testHelpCommand(): void
    {
        $output = `$this->command --help 2>&1`;
        $this->assertMatchesRegularExpression('/Usage instructions/m',$output, 'Could not find the usage instructions');
    }

    public function testNoParameters(): void
    {
        $output = `$this->command 2>&1`;
        $this->assertMatchesRegularExpression('/Choose an option!/m',$output, 'Could not find the choose an option title');
    }

    public function testFileExample(): void
    {
        $output = `$this->command --file fixtures/lorem.txt 2>&1`;
        $this->assertMatchesRegularExpression('/Discovered words between brackets/m',$output, 'Could not find the choose an option title');
        $this->assertMatchesRegularExpression('/Pellentesque/m',$output, 'Could not find the word Pellentesque');
        $this->assertMatchesRegularExpression('/lacinia/m',$output, 'Could not find the word lacinia');
    }
}