<?php

class AuditingTest extends TestCase
{
    public function testStarterCommand()
    {
        $this->artisan('luissobrinho:auditing')
            ->expectsQuestion('Are you sure you want to overwrite any files of the same name?', true)
            ->assertExitCode(0);

        $this->assertTrue(file_exists(base_path('app/Listeners/AuditedListener.php')));
        $this->assertTrue(file_exists(base_path('app/Listeners/AuditingListener.php')));
    }
}
