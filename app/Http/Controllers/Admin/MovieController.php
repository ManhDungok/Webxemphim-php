<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Movie;
use App\Models\Nation;

class MovieController extends Controller
{
    //Dsach phim
    public function index()
    {
        $movies = Movie::paginate(config('view.default_pagination'));
        $data = [
            'movies' => $movies,
        ];

        return view('admin.movies.index', $data);
    }

    //Form tạo mới phim
    public function create()
    {
        // Lấy tất cả các danh mục (categories) từ cơ sở dữ liệu
        $categories = Category::all();
        // Lấy tất cả các quốc gia (nations) từ cơ sở dữ liệu
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
        $movie->release_date = request()->release_date; //ngày phát hành
        $movie->duration = request()->duration; //khoảng thời gian
        $movie->category_id = request()->category_id; //Thể loại 
        $movie->nation_id = request()->nation_id;
        $movie->status = request()->status;
        $movie->save();

        return redirect()->route('movies.index');
    }

    public function show($id)
    {
        $movie = Movie::find($id);
        $categories = Category::all(); //Thể loại
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

    //Update phim
    public function update($id)
    {
        $movie = Movie::find($id);
        $movie->name = request()->name;
        $movie->description = request()->description; // miêu tả
        $image = request()->file('image');
        if ($image) { //Nếu có ảnh thì:
            $hashed_image_name = $image->hashName();
            //Dòng này lưu trữ tệp hình ảnh trong thư mục images dưới thư mục gốc public_uploads
            //storeAs lưu tệp hình ảnh với tên đã được băm
            //Đường dẫn của tệp hình ảnh được lưu trữ sẽ được gán cho thuộc tính image của đối tượng $movie.
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