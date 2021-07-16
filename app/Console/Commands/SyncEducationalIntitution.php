<?php

namespace App\Console\Commands;

use App\Jobs\SyncEducationalInsitutions;
use Illuminate\Console\Command;

class SyncEducationalIntitution extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:education';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will sync data with dapodik data via api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SyncEducationalInsitutions::dispatchNow();
    }
}
