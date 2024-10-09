<?php

namespace Bishopm\Church\Console\Commands;

use Illuminate\Console\Command;

class InstallChurch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('filament:install', ["--panels" => true]);
        $this->call('make:filament-user');
        $this->call('storage:link');
        echo("All done!");
    }
}
