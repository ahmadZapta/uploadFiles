<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class TempController extends Controller
{
    public function create(Request $request)
    {


        $imagePaths = [];
        foreach ($request->file('documents') as $file) {
            $imagePaths[] = $file->store('images', 'public');
        }
        foreach ($imagePaths as $path) {
            Image::create([
                'documents' => $path,
            ]);
        }

        return view('frontend.create');
    }
    public function edit(Request $request)
    {
        $uploadedImages = Image::all();

        return view('frontend.edit', ['files'=>$uploadedImages]);
    }
    public function update(Request $request)
    {

        dd($request->all());
        return "update";
    }
}