<?php

namespace Luissobrinho\Builder\Console;

use Luissobrinho\Builder\Console\LuissobrinhoCommand;
use Luissobrinho\Builder\Traits\FileMakerTrait;
use Illuminate\Filesystem\Filesystem;

class Auditing extends LuissobrinhoCommand
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'luissobrinho:auditing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Luissobrinho Builder will add a auditing to your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->starterIsInstalled()) {
            $fileSystem = new Filesystem();

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Auditing');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Auditing/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Auditing', base_path());
                $this->info("\n\n Please review the audit setup details.");
                $this->info("\n Audit setup completed");
            } else {
                $this->info("\n You cancelled the luissobrinho:auditing");
            }
        }
    }
}
