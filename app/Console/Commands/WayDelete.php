<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
//use App\Way;
use Illuminate\Support\Facades\DB;
//use Auth;

class WayDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'way:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will clean Delay way of deliverman';

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
    $pending_ways = DB::table('ways')->where('status_code','!=',001)->where('status_code','!=',002)->where('deleted_at',null)->orderBy('id','desc')->delete();
    echo "operation success";
}
}
