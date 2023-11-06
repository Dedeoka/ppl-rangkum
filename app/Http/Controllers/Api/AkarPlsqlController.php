<?php

namespace App\Http\Controllers\Api;

use App\Models\AkarKuadrat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;


class AkarPlsqlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = AkarKuadrat::where('metode', 'PlSql')->get();
        $sortedData = $data->sortBy('waktu');

        $fastest = $sortedData->first();
        $slowest = $sortedData->last();

        $fastestAkarKuadrat = $fastest->waktu;
        $slowestAkarKuadrat = $slowest->waktu;

        // Buat array JSON yang berisi data tercepat dan terlama
        $jsonData = [
            'fastest' => $fastestAkarKuadrat,
            'slowest' => $slowestAkarKuadrat,
        ];

        // Kirim data JSON ke frontend
        return response()->json($jsonData);

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

        $bilangan = $request->bilangan;
        $nim = $request->nim;
        $startTime = microtime(true);

        $result = DB::select('CALL CalculateSquareRoot(?, @akar_kuadrat)', [$bilangan]);

        $kuadrat_manual = DB::select('SELECT @akar_kuadrat AS akar_kuadrat')[0]->akar_kuadrat;


        $data = new AkarKuadrat;
        $data->nim = $nim;
        $data->bilangan = $bilangan;
        $data->akar_kuadrat = $kuadrat_manual;
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 10;
        $data->metode = 'PlSql';
        $data->waktu = $executionTime;
        $data->save();

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
