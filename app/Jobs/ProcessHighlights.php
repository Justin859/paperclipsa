<?php

namespace App\Jobs;

use App\ClipHighlight;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessHighlights implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stream_id;
    protected $clip_name;
    protected $time;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($stream_id, $clip_name, $time)
    {
        //
        $this->$stream_id = $stream_id;
        $this->$clip_name = $clip_name;
        $this->$time = $time;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $stream = \App\Stream::find($this->stream_id);
        $client = new \GuzzleHttp\Client();

        $clip_name = str_replace(",", "_", 'Land_Reform_F.C._VS_Benfica_Stars_2018_06_17_19-19-01,00:00:45');
        $clip_complete_name = str_replace(":", "-", $clip_name);

        $r = $client->request('POST', 'http://127.0.0.1:5002/highlights', [
            'json' => ['file_name' => 'Land_Reform_F.C._VS_Benfica_Stars_2018_06_17_19-19-01', 'start_time' => '00:00:45', 'highlight_name' => $clip_complete_name]
        ]);
    }
}
