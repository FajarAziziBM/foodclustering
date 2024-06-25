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

            $responseData = json_decode($response->getBody()->getContents(), true);

            // Proses respons JSON dari Flask
            if (isset($responseData['data'])) {
                $data = $responseData['data'];

                // Periksa keberadaan 'results' dalam respons
                if (isset($data['results'])) {
                    $results = $data['results'];
                } else {
                    throw new Exception('Data "results" tidak ditemukan dalam respons');
                }

                // Periksa keberadaan 'province_clustered_data' dalam respons
                if (isset($data['province_clustered_data'])) {
                    $bestlabelsnamed = $data['province_clustered_data'];
                } else {
                    throw new Exception('Data "province_clustered_data" tidak ditemukan dalam respons');
                }
            } else {
                throw new Exception('Data "data" tidak ditemukan dalam respons');
            }

            // Dispatch job untuk memproses data klastering
            ProcessClusteringList::dispatch($results, $bestlabelsnamed);

            // Berhasil jika tidak ada exception yang dilempar
            return response()->json(['message' => 'Clustering results queued successfully']);

        } catch (Exception $e) {
            // Tangani kesalahan dengan mengembalikan respons JSON
            return response()->json([
                'success' => false,
                'message' => 'Error processing data: ' . $e->getMessage(),
            ], 500); // Kode status 500 untuk kesalahan server
        }
    }

}
