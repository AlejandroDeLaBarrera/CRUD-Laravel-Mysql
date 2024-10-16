<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateHobbiesJob;

class UpdateHobbiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hobbies:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar hobbies de customers desde API externa';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Ejecutar el Job
        UpdateHobbiesJob::dispatch();

        $this->info('Actualización de hobbies iniciada');
    }
}
