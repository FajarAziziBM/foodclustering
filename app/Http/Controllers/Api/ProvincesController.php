<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clustering;
use App\Models\Province;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PSpell\Config;

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
                'tahun' => $request->tahun,
                'namaprovinsi' => [],
                'luaspanen' => [],
                'produktivitas' => [],
                'produksi' => [],
            ];

            // Loop melalui data provinces untuk mengisi array $datasProv
            foreach ($provinces as $item) {
                $datasProv['namaprovinsi'][] = $item->namaprovinsi;
                $datasProv['luaspanen'][] = $item->luaspanen;
                $datasProv['produktivitas'][] = $item->produktivitas;
                $datasProv['produksi'][] = $item->produksi;
            }

            $jsonDataProv = json_encode($datasProv);

            // Kirim data ke Flask untuk clustering
            $client = new Client();
            $flask_url = 'http://127.0.0.1:5000/clustering';
            $response = $client->post($flask_url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => $jsonDataProv,
            ]);

            // Mendapatkan status kode respons dan data respons dari Flask
            $statusCode = $response->getStatusCode();
            $responseData = $response->getBody()->getContents();

            // Periksa apakah respons dari Flask adalah sukses
            if ($statusCode !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process data: ' . $responseData,
                ], $statusCode);
            }

            // Mengembalikan respons JSON berhasil dengan data dari Flask
            return response()->json([
                'success' => true,
                'message' => 'Data successfully sent to clustering endpoint',
                'data' => json_decode($responseData, true), // Decode JSON response from Flask
            ]);
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



    public function hasilcluster(Request $request){
        try {

            // Mendapatkan data hasil clustering
            $results = $request->input('results');
            $bestConfig = $request->input('best_config');
            $provinceClusteredData = $request->input('province_clustered_data');


            // Ambil data berdasarkan ID dari request
            $savecluster = $results->Clustering::findOrFail();

            // Simpan perubahan ke database
            $savecluster->save();

            // Di sini Anda dapat menyimpan data ke dalam database atau melakukan operasi lainnya sesuai kebutuhan
            // Misalnya, menyimpan hasil clustering ke dalam tabel tertentu

            // Contoh penyimpanan data hasil clustering
            $savedData = [
                'results' => json_decode($results, true),
                'best_config' => json_decode($bestConfig, true),
                'province_clustered_data' => $provinceClusteredData,
            ];

            // Lakukan operasi penyimpanan data ke database atau yang sesuai dengan kebutuhan aplikasi Anda

            return response()->json(['message' => 'Clustering results successfully saved in Laravel', 'data' => $savedData]);
        } catch (\Exception $e) {
            Log::error('Error saving clustering results: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save clustering results'], 500);
        }
    }
}
