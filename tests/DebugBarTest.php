<?php

class DebugBarTest extends TestCase
{
    public function testStarterCommand()
    {
        $this->artisan('luissobrinho:debugbar')
            ->expectsQuestion('Are you sure you want to overwrite any files of the same name?', true)
            ->assertExitCode(0);

        $this->assertTrue(file_exists(base_path('app/Http/Middleware/DebugMiddleware.php')));
    }
}
