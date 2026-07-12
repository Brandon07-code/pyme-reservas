<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\Request;

class ExternalPostController extends Controller
{
    public function index(PostService $postService)
    {
        try {
            $posts = $postService->getAllPosts();
            
            // Traemos solo los primeros 12 para que la vista se vea elegante y no saturada
            $posts = array_slice($posts, 0, 12); 
            
            return view('posts.index', compact('posts'));
        } catch (\Exception $e) {
            return view('posts.index', ['error' => 'Servicio externo (JSONPlaceholder) no disponible temporalmente.']);
        }
    }
}