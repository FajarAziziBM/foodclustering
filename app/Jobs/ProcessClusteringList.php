<?php

namespace App\Jobs;

use App\Models\Clustering;
use App\Models\HasilCluster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessClusteringList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $results;
    protected $provinceClusteredData;

    /**
     * Create a new job instance.
     *
     * @param array $results
     * @param array $provinceClusteredData
     */
    public function __construct(array $results, array $provinceClusteredData)
    {
        $this->results = $results;
        $this->provinceClusteredData = $provinceClusteredData;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            foreach ($this->results as $data) {
                $cluster = new Clustering();

                $cluster->eps = $data['EPS'];
                $cluster->minpts = $data['MINPTS'];
                $cluster->jmlcluster = $data['NUM_CLUSTERS'];
                $cluster->jmlnoice = $data['NUM_NOISE'];
                $cluster->jmltercluster = $data['NUM_CLUSTERED'];
                $cluster->silhouette_index = $data['SILHOUETTE_INDEX'];
                $cluster->save();

                foreach ($this->provinceClusteredData as $label) {
                    $finalcluster = new HasilCluster();
                    $finalcluster->cluster = $label['NUM_CLUSTERS'];
                    $finalcluster->anggota_cluster = $label['namaprovinsi'];
                    $finalcluster->clusterId = $cluster->id;
                    $finalcluster->save();
                }
            }

            Log::info('Clustering results processed successfully');
        } catch (\Exception $e) {
            Log::error('Error processing clustering results: ' . $e->getMessage());
        }
    }
}
