<?php

namespace App\Http\Controllers\Api;

use App\Models\AkarKuadrat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;


class AkarApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = AkarKuadrat::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'bilangan' => [
                'required',
                'regex:/^(\d+(\.\d{1,10})?)$/',
                function ($attribute, $value, $fail) {
                    if ($value < 0) {
                        $fail('Tidak dapat menginputkan bilangan negatif pada form.');
                    }
                },
            ],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Hitung kuadrat bilangan
        $nim = $request->nim;
        $bilangan = $request->bilangan;
        $startTime = microtime(true);

        // Inisialisasi $kuadrat_manual
        $kuadrat_manual = 0;

        // Perhitungan manual akar kuadrat
        if ($bilangan != 0) {
            $x = $bilangan / 2;
            for ($i = 0; $i < 1000; $i++) { // Batasi iterasi ke 1000 untuk menghindari perulangan tak terbatas
                $estimate = 0.5 * ($x + $bilangan / $x);
                if (abs($estimate - $x) < 1e-6) {
                    $kuadrat_manual = $estimate;
                    break;
                }
                $x = $estimate;
            }
        }

        // Simpan bilangan, hasil kuadrat, dan waktu eksekusi ke dalam database
        $data = new AkarKuadrat;
        $data->nim = $nim;
        $data->bilangan = $bilangan;
        $data->akar_kuadrat = $kuadrat_manual;
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        $data->metode = 'Api';
        $data->waktu = $executionTime;
        $data->save();

        // Mengembalikan respons JSON yang berisi bilangan terakhir, hasil kuadrat, dan waktu eksekusi
        return response()->json([
            'bilangan_terakhir' => $bilangan,
            'hasil_kuadrat' => $kuadrat_manual,
            'waktu_eksekusi' => $executionTime,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
