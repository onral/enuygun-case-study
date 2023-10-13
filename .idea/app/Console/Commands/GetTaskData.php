<?php

namespace App\Console\Commands;

use App\Facades\Provider1Facade;
use App\Facades\Provider2Facade;
use Illuminate\Console\Command;

class GetTaskData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-task-data {providerNo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets task data from apis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Provider1Facade::getData();
        Provider2Facade::getData();
    }
}
