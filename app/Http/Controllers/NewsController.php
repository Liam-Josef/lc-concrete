<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function home_news() {
        $news = News::all();

        return view('home/news', [
            'news' => $news,
        ]);
    }
    public function home_show(News $news) {

        return view('home/news-post', [
            'news' => $news,
        ]);
    }
    public function create(News $news) {

        return view('admin/news/create', [
            'news' => $news,
        ]);
    }

}
