<?php

namespace App\Http\Controllers\Api;

use App\Models\AkarKuadrat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function index(){
        $data = AkarKuadrat::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function dataApi(){
        $data = AkarKuadrat::where('metode', 'Api')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function dataPlsql(){
        $data = AkarKuadrat::where('metode', 'PlSql')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function dataUser(){
        $data = AkarKuadrat::select('nim',
        DB::raw('SUM(CASE WHEN metode = "Api" THEN 1 ELSE 0 END) as Api'),
        DB::raw('SUM(CASE WHEN metode = "Plsql" THEN 1 ELSE 0 END) as Plsql'))
        ->groupBy('nim')
        ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('total', function($data){
                $api = $data->Api;
                $plsql = $data->Plsql;
                $total = $api + $plsql;
                return $total;
            })
            ->make(true);
        }
}
