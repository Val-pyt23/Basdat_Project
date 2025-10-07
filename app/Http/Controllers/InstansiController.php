<?php
namespace App\Http\Controllers;
use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $instansis = Instansi::where('type', $type)->orderBy('name')->get();
        return response()->json($instansis);
    }
}