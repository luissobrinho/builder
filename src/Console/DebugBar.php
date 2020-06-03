<?php

namespace Luissobrinho\Builder\Console;

use Luissobrinho\Builder\Console\LuissobrinhoCommand;
use Luissobrinho\Builder\Traits\FileMakerTrait;
use Illuminate\Filesystem\Filesystem;

class DebugBar extends LuissobrinhoCommand
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'luissobrinho:debugbar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Luissobrinho Builder will add a debug bar listing UI to your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->starterIsInstalled()) {
            $fileSystem = new Filesystem();

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/DebugBar');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/DebugBar/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/DebugBar', base_path());
                $this->info("\n\n Please review the setup details for debug bar.");
                $this->info("\n\n You will want to add things like:");
                $this->line("\n Insert this code in the Kernel.php file for the $ middlewareGroups variable in the web index: ");
                $this->comment("\n protected \$middlewareGroups = [");
                $this->comment("\n 'web' => [");
                $this->comment("\n ...");
                $this->comment("\n \App\Http\Middleware\DebugMiddleware::class");
                $this->comment("\n ],");
                $this->info("\n Finished setting up debug bar");
            } else {
                $this->info("\n You cancelled the luissobrinho:debugbar");
            }
        }
    }
}
