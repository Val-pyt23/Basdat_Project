<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Menampilkan halaman "Tentang Layanan".
     */
    public function about()
    {
        return view('pages.about');
    }
}
