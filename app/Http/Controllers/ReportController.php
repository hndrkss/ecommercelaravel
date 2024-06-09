<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 'indexx']);
    }

    public function indexx(Request $request) 
    {
        dd($request->all());
        $report = DB::table('order_detail')
            ->join('products', 'products.id', '=', 'order_detail.id_produk')
            ->select(DB::raw(' 
            nama_barang,
            count(*) as jumlah_dibeli,
            harga,
            SUM(total) as pendapatan,
            SUM(jumlah) as total_qty'))
            ->whereBetween('order_detail.created_at', [$request->dari, $request->sampai])
            ->whereRaw("date(order_detail.created_at)>= '$request->dari'")
            ->whereRaw("date(order_detail.created_at)<= '$request->sampai'")
            ->groupBy('id_produk', 'nama_barang', 'harga')
            ->get();
        
        return response()->json([
            'data' => $report
        ]);
    }
}
