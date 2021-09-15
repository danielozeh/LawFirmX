<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\ClientController as Client;

/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */

class ProfilePictureReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify All Clients who are yet to submit passport';

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
        $client = new Client();
        $client->notifyClientWithNullProfile();
    }
}
