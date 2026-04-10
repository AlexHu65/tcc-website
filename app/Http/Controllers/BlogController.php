<?php

namespace App\Http\Controllers;

use Statamic\Facades\Entry;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Entry::query()
            ->where('collection', 'blog')
            ->whereStatus('published')
            ->orderByDesc('date')
            ->paginate(9);

        return view('blog.index', [
            'posts' => $posts,
            'title' => 'Blog',
            'content' => 'Articulos y guias para fortalecer tu bienestar emocional.',
        ]);
    }
}
