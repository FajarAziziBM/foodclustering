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
    protected $bestlabelsnamed;

    /**
     * Create a new job instance.
     *
     * @param array $results
     * @param array $provinceClusteredData
     */
    public function __construct(array $results, array $bestlabelsnamed)
    {
        $this->results = $results;
        $this->bestlabelsnamed = $bestlabelsnamed;
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
                $cluster->tahun = $data['year'];
                $cluster->save();
            }

            $clusteredData = $this->bestlabelsnamed['province_clustered_data'];

            foreach ($clusteredData as $clusterName => $provinces) {
                if ($clusterName === 'year') {
                    continue;
                }
                foreach ($provinces as $province) {
                    $hasilCluster = new HasilCluster();
                    $hasilCluster->cluster = $clusterName;
                    $hasilCluster->anggota_cluster = $province;
                    $hasilCluster->tahun = $clusteredData['year']; // Assuming 'year' is in the top level
                    $hasilCluster->save();
                }
            }

            Log::info('Clustering results processed successfully');
        } catch (\Exception $e) {
            // Handle exception
            Log::error('Error saving cluster results: ' . $e->getMessage());
            throw $e;
        }
    }
}
