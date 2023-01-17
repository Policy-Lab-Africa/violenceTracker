<?php

namespace App\Jobs\Data;

use Illuminate\Bus\Queueable;
use App\Models\NgLocalGovernment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class GenerateLgasData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $stateDirs = Storage::directories(config('inecdata.path'));
        foreach($stateDirs as $stateDir)
        {
            $stateLga = Storage::directories($stateDir);
            foreach($stateLga as $stateLga)
            {
                $lgas = collect(json_decode(Storage::get($stateLga.'/index.json')));
                foreach($lgas as $lga)
                {
                    // 
                    NgLocalGovernment::updateOrCreate([
                        'data_id' => $lga->id,
                        'name' => $lga->name,
                        'abbreviation' => $lga->abbreviation,
                        'state_id' => $lga->state_id,
                    ]);
                }

                
            }
        }

    }
}