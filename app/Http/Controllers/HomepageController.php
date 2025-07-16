<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    /**
     * Display the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data['coffees'] = Produk::where('kategori', 'kopi')->get();
        $data['nonCoffees'] = Produk::where('kategori', '!=', 'kopi')->get();
        return view('homepage.menu', $data);
    }

    /**
     * Display the about page.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('homepage.about');
    }

    /**
     * Display the contact page.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('homepage.contact');
    }
}
