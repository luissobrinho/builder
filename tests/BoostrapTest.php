<?php

use Illuminate\Support\Str;

class BootstrapTest extends TestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('luissobrinho:starter')
            ->expectsQuestion('Are you sure you want to overwrite any files of the same name?', true)
            ->expectsQuestion('Would you like to run the migration?', false)
            ->assertExitCode(0);
    }

    public function testBootstrapCommand()
    {
        $this->artisan('luissobrinho:bootstrap')
            ->expectsQuestion('Are you sure you want to overwrite any files of the same name?', true)
            ->assertExitCode(0);
    }

    public function testFilesExist()
    {
        $contents = file_get_contents(base_path('resources/views/dashboard/panel.blade.php'));
        $this->assertTrue(Str::contains($contents, '<a class="nav-link"'));
        $this->assertTrue(file_exists(base_path('resources/sass/_base.scss')));
    }
}
