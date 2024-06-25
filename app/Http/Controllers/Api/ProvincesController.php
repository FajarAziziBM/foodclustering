<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessClusteringList;
use App\Models\Clustering;
use App\Models\HasilCluster;
use App\Models\Province;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PSpell\Config;
use Illuminate\Support\Facades\Validator;

class ProvincesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Ambil data dari database berdasarkan tahun
            $provinces = Province::where('tahun', $request->tahun)->get();

            // Validasi apakah data ditemukan
            if ($provinces->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found for year ' . $request->tahun,
                ]);
            }

            // Inisialisasi array untuk menyimpan data provinsi
            $datasProv = [
                'id' => [],
                'tahun' => $request->tahun,
                'namaprovinsi' => [],
                'luaspanen' => [],
                'produktivitas' => [],
                'produksi' => [],
            ];

            // Loop melalui data provinces untuk mengisi array $datasProv
            foreach ($provinces as $item) {
                $datasProv['id'][] = $item->id;
                $datasProv['namaprovinsi'][] = $item->namaprovinsi;
                $datasProv['luaspanen'][] = $item->luaspanen;
                $datasProv['produktivitas'][] = $item->produktivitas;
                $datasProv['produksi'][] = $item->produksi;
            }

            $jsonDataProv = json_encode($datasProv);

            // Kirim data ke Flask untuk clustering
            $client = new Client();
            $flask_url = 'http://127.0.0.1:8088/clustering';
            $response = $client->post($flask_url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => $jsonDataProv,
            ]);

            // Mendapatkan status kode respons dan data respons dari Flask
            $statusCode = $response->getStatusCode();
            $responseData = $response->getBody()->getContents();
            $data = json_decode($responseData, true);
            // Ambil data yang divalidasi

            if (isset($data['data'])) {
                $data = $data['data']; // Ambil data di dalam kunci 'data'

                // Sekarang Anda bisa mengakses 'province_clustered_data' dan 'results'
                if (isset($data['province_clustered_data'])) {
                    $best_labels_named = $data['province_clustered_data'];
                    // Sekarang $best_labels_named berisi nilai dari province_clustered_data
                    // Debug untuk memastikan data berhasil diambil
                } else {
                    // Handle jika 'province_clustered_data' tidak ada
                    throw new Exception('Data "province_clustered_data" tidak ditemukan dalam respons');
                }

                // Misalnya, jika Anda juga memerlukan 'results'
                if (isset($data['results'])) {
                    $results = $data['results'];
                    // Lakukan sesuatu dengan $results

                } else {
                    // Handle jika 'results' tidak ada
                    throw new Exception('Data "results" tidak ditemukan dalam respons');
                }
            } else {
                // Handle jika 'data' tidak ada dalam respons
                throw new Exception('Data "data" tidak ditemukan dalam respons');
            }
            dd($results);
            dd($best_labels_named);

                // Dispatch the job to process clustering results
            ProcessClusteringList::dispatch($results, $best_labels_named);

            // Periksa apakah respons dari Flask adalah sukses
            if ($statusCode !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process data: ' . $responseData,
                ], $statusCode);
            }

            return response()->json(['message' => 'Clustering results queued successfully']);
        } catch (\Exception $e) {
            // Menangkap kesalahan dan logging
            Log::error('Error processing data: ' . $e->getMessage());

            // Mengembalikan respons JSON gagal jika terjadi kesalahan
            return response()->json([
                'success' => false,
                'message' => 'Failed to process data: ' . $e->getMessage(),
            ], 500);
        }
    }

}
