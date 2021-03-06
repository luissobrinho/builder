<?php

class SocialiteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('luissobrinho:starter')
            ->expectsQuestion('Are you sure you want to overwrite any files of the same name?', true)
            ->expectsQuestion('Would you like to run the migration?', false)
            ->assertExitCode(0);
    }

    public function testApiCommand()
    {
        $this->artisan('luissobrinho:socialite')
            ->expectsQuestion('Are you sure you want to overwrite any files of the same name?', true)
            ->assertExitCode(0);
    }

    public function testFilesExist()
    {
        $this->assertTrue(file_exists(base_path('app/Http/Controllers/Auth/SocialiteAuthController.php')));
    }
}
