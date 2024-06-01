<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(config('view.default_pagination'));

        $data = [
            'categories' => $categories,
        ];

        return view('admin.categories.index', $data);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store()
    {
        $category = new Category();
        $category->name = request()->name;
        $category->save();

        return redirect()->route('categories.index');
    }

    public function show($id)
    {
        $category = Category::find($id);

        $data = [
            'category' => $category,
        ];

        return view('admin.categories.show', $data);
    }

    public function edit($id)
    {
        $category = Category::find($id);

        $data = [
            'category' => $category,
        ];

        return view('admin.categories.edit', $data);
    }

    public function update($id)
    {
        $category = Category::find($id);
        $category->name = request()->name;
        $category->save();

        return redirect()->route('categories.index');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return redirect()->route('categories.index');
    }
}
