<?php

// Retrieve all open articles
$articles = Article::where('status', 1)->orderBy('created_at', 'desc')->select('id', 'status', 'title', 'thumbnails', 'slug')->take(3)->get()->toArray();

// Add thumbnails images and article url to array
foreach ($articles as $key => $value) {
    $articles[$key]['title']        = Str::limit($articles[$key]['title'], 15);
    $articles[$key]['thumbnails']   = URL::to('/upload/thumbnails') . '/' . $articles[$key]['thumbnails'];
    $articles[$key]['url']          = URL::to('/article') . '/' . $articles[$key]['slug'];
}

// Build Json format
return Response::json(
    array(
        'status'    => 1,
        'data'      => $articles
    )
);