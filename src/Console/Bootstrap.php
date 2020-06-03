<?php

namespace Luissobrinho\Builder\Console;

use Illuminate\Support\Facades\Artisan;
use Luissobrinho\Builder\Console\LuissobrinhoCommand;
use Luissobrinho\Builder\Traits\FileMakerTrait;
use Illuminate\Filesystem\Filesystem;
use mysql_xdevapi\Exception;

class Bootstrap extends LuissobrinhoCommand
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'luissobrinho:bootstrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Luissobrinho Builder will bootstrap your app';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->starterIsInstalled()) {
            $fileSystem = new Filesystem();

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Bootstrap');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Bootstrap/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            if (env('APP_NO_TEST', true)) {
                Artisan::call('ui', ['type' => 'bootstrap']);
            }

            $this->info("\n\nRun -> php artisan ui bootstrap\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?', true);

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Bootstrap/', base_path());

                $this->line("\nMake sure you set the PagesController@dashboard to use the following view:\n");
                $this->comment("'dashboard.main'\n");
                $this->info("Run the following:\n");
                $this->comment("npm install <- auto exec\n");
                $this->comment("npm run dev <- local development (auto exec)\n");
                $this->comment("npm run watch <- watch for changes\n");
                $this->comment("npm run production <- run for production\n");
                $this->info("Finished bootstrapping your app\n");

                $this->info("npm install && npm run dev\n");
                exec(sprintf('npm install && npm run dev', escapeshellarg(base_path())));
            } else {
                $this->info('You cancelled the luissobrinho:bootstrap');
            }
        }
    }
}
