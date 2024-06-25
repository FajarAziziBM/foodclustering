<?php

namespace App\Jobs;

use App\Models\HasilCluster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessBestLabels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bestLabelsNamed;

    /**
     * Create a new job instance.
     *
     * @param array $bestLabelsNamed
     */
    public function __construct(array $bestLabelsNamed)
    {
        $this->bestLabelsNamed = $bestLabelsNamed;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            foreach ($this->bestLabelsNamed as $label) {
                $finalcluster = new HasilCluster();
                $finalcluster->cluster = $label['NUM_CLUSTERS'];
                $finalcluster->anggota_cluster = $label['namaprovinsi'];
                $finalcluster->tahun = $label['tahun'];
                $finalcluster->save();
            }

            Log::info('Best labels named processed successfully');

        } catch (\Exception $e) {
            Log::error('Error processing best labels named: ' . $e->getMessage());
        }
    }
}
