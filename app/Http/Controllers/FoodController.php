<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Requests\FoodRequest;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $foods = Food::paginate(10);
        return view('foods.index', compact('foods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('foods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FoodRequest $request)
    {
        $save_file = null;
        if($request->file('picturePath')){
            $file = $request->file('picturePath');
            $file_name = 'photo-food-'.$request->name.".".$file->extension();
            $save_file = $file->storeAs('assets/foods', $file_name, 'public');
        }
        Food::create([
            'name' => $request->name,
            'description' => $request->description,
            'picturePath' => $save_file,
            'ingredients' => $request->ingredients,
            'price' => $request->price,
            'rating' => $request->rating,
            'type' => $request->type
        ]);

        return redirect()->route('foods.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Food $food)
    {
        //
        $food = Food::findOrFail($food->id);

        return view('foods.edit', ['food' => $food]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Food $food)
    {
        //
        $food = Food::findOrFail($food->id);
        $data = $request->all();
        // $save_file = null;
        if($request->file('picturePath')){
            if($food->picturePath && file_exists('app/public/'.$food->picturePath)){
                Storage::delete('public/'.$food->picturePath);
            }
            $file = $request->file('picturePath');
            $file_name = 'photo-food-'.$request->name.".".$file->extension();
            $data["picturePath"] = $file->storeAs('assets/foods', $file_name, 'public');
        }

        $food->update($data);

        return redirect()->route('foods.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        //
        //
        $food = Food::findOrFail($food->id);
        // $path = public_path().$user->profile_photo_path;
        // // echo $path;
        $food->delete();
        if($food->picturePath && file_exists('app/public/'.$food->picturePath)){
            Storage::delete('public/'.$food->picturePath);
        }
        // unlink($path);

        return redirect()->route('foods.index');
    }
}
