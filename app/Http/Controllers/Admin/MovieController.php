<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Movie;
use App\Models\Nation;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::paginate(config('view.default_pagination'));
        $data = [
            'movies' => $movies,
        ];

        return view('admin.movies.index', $data);
    }

    public function create()
    {
        $categories = Category::all();
        $nations = Nation::all();

        $data = [
            'categories' => $categories,
            'nations' => $nations,
        ];

        return view('admin.movies.create', $data);
    }

    public function store()
    {
        $movie = new Movie();
        $movie->name = request()->name;
        $movie->description = request()->description;
        $image = request()->file('image');
        $hashed_image_name = $image->hashName();
        $movie->image = $image->storeAs('images', $hashed_image_name, 'public_uploads');
        $video = request()->file('video');
        $hashed_video_name = $video->hashName();
        $movie->video = $video->storeAs('videos', $hashed_video_name, 'public_uploads');
        $movie->trending = request()->trending ?? 0;
        $movie->price = request()->price ?? 0;
        $movie->point = request()->point;
        $movie->release_date = request()->release_date;
        $movie->duration = request()->duration;
        $movie->category_id = request()->category_id;
        $movie->nation_id = request()->nation_id;
        $movie->status = request()->status;
        $movie->save();

        return redirect()->route('movies.index');
    }

    public function show($id)
    {
        $movie = Movie::find($id);
        $categories = Category::all();
        $nations = Nation::all();

        $data = [
            'movie' => $movie,
            'categories' => $categories,
            'nations' => $nations,
        ];

        return view('admin.movies.show', $data);
    }

    public function edit($id)
    {
        $movie = Movie::find($id);
        $categories = Category::all();
        $nations = Nation::all();

        $data = [
            'movie' => $movie,
            'categories' => $categories,
            'nations' => $nations,
        ];

        return view('admin.movies.edit', $data);
    }

    public function update($id)
    {
        $movie = Movie::find($id);
        $movie->name = request()->name;
        $movie->description = request()->description;
        $image = request()->file('image');
        if ($image) {
            $hashed_image_name = $image->hashName();
            $movie->image = $image->storeAs('images', $hashed_image_name, 'public_uploads');
        }
        $video = request()->file('video');
        if ($video) {
            $hashed_video_name = $video->hashName();
            $movie->video = $video->storeAs('videos', $hashed_video_name, 'public_uploads');
        }
        $movie->trending = request()->trending ?? 0;
        if (request()->price) {
            $movie->price = request()->price;
        }
        $movie->point = request()->point;
        $movie->release_date = request()->release_date;
        $movie->duration = request()->duration;
        $movie->category_id = request()->category_id;
        $movie->nation_id = request()->nation_id;
        $movie->status = request()->status;
        $movie->save();

        return redirect()->route('movies.index');
    }

    public function destroy($id)
    {
        $movie = Movie::find($id);
        $movie->delete();

        return redirect()->route('movies.index');
    }
}