<?php
use PhpBrew\Testing\CommandTestCase;
use PhpBrew\Machine;
use PhpBrew\Config;

/**
 * The install command tests are heavy.
 *
 * Don't catch the exceptions, the system command exception 
 * will show up the error message.
 *
 * Build output will be shown when assertion failed.
 *
 * @large
 * @group command
 */
class InstallCommandTest extends CommandTestCase
{
    /**
     * @group install
     */
    public function testKnownCommand()
    {
        $this->assertCommandSuccess("phpbrew init");
        $this->assertCommandSuccess("phpbrew known --update");
    }

    /**
     * @depends testKnownCommand
     * @group install
     */
    public function testInstallCommand()
    {
        $versionName = $this->getPrimaryVersion();
        $processorNumber = Machine::getInstance()->detectProcessorNumber();
        $jobs = is_numeric($processorNumber) ? "--jobs $processorNumber" : "";
        $this->assertCommandSuccess("phpbrew install $jobs php-{$versionName} +default +intl");
        $this->assertListContains("php-{$versionName}");
    }

    /**
     * @depends testInstallCommand
     */
    public function testUseCommand()
    {
        $versionName = $this->getPrimaryVersion();
        $this->assertCommandSuccess("phpbrew use php-{$versionName}");
    }

    /**
     * @depends testInstallCommand
     */
    public function testCtagsCommand()
    {
        $versionName = $this->getPrimaryVersion();
        $this->assertCommandSuccess("phpbrew ctags php-{$versionName}");
    }

    /**
     * @depends testInstallCommand
     * @group install
     */
    public function testInstallAsCommand()
    {
        $versionName = $this->getPrimaryVersion();
        $processorNumber = Machine::getInstance()->detectProcessorNumber();
        $jobs = is_numeric($processorNumber) ? "--jobs $processorNumber" : "";
        $this->assertCommandSuccess("phpbrew --debug install {$jobs} php-{$versionName} as myphp +soap");
        $this->assertListContains("myphp");
    }

    /**
     * @depends testInstallCommand
     */
    public function testCleanCommand()
    {
        $versionName = $this->getPrimaryVersion();
        $this->assertCommandSuccess("phpbrew --quiet clean php-{$versionName}");
    }

    protected function assertListContains($string)
    {
        var_dump(Config::findInstalledBuilds(false));
        $this->assertNotEmpty(Config::findInstalledBuilds(false), 'findInstalledBuilds');
        $this->assertContains($string, Config::findInstalledBuilds(false));
    }
}
