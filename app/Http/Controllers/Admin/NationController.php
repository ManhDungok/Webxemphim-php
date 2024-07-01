<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nation;
use Illuminate\Http\Request;

class NationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //Dsach quốc gia
    public function index()
    {
        $nations = Nation::paginate(config('view.default_pagination'));

        $data = [
            'nations' => $nations,
        ];

        return view('admin.nations.index', $data);
    }

    public function create()
    {
        return view('admin.nations.create');
    }

    public function store()
    {
        $nation = new Nation();
        $nation->name = request()->name;
        $nation->save();

        return redirect()->route('nations.index');
    }

    public function show($id)
    {
        $nation = Nation::find($id);

        $data = [
            'nation' => $nation,
        ];

        return view('admin.nations.show', $data);
    }

    public function edit($id)
    {
        $nation = Nation::find($id);

        $data = [
            'nation' => $nation,
        ];

        return view('admin.nations.edit', $data);
    }

    public function update($id)
    {
        $nation = Nation::find($id);
        $nation->name = request()->name;
        $nation->save();

        return redirect()->route('nations.index');
    }

    public function destroy($id)
    {
        $nation = Nation::find($id);
        $nation->delete();

        return redirect()->route('nations.index');
    }
}