<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PostService
{
    protected $url = 'https://jsonplaceholder.typicode.com/posts';

    public function getAllPosts(): array
    {
        $response = Http::get($this->url);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }
}