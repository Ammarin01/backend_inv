<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Read all product
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //เช็คสิท role ว่าเป็น admin (1)
        $user = auth()->user();
        if ($user->tokenCan("1")) {
            # code...
            $request->validate([
                'name' => 'required|min:5',
                'slug' => 'required',
                'price' => 'required',
            ]);
            return Product::create($request->all());
        } else {
            return [
                'status' => 'Permission denied to create',
            ];
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->tokenCan("1")) {
            # code...

            $product = Product::find($id);
            $product->update($request->all());
            return $product;
        } else {
            return [
                'status' => 'Permission denied to create',
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->tokenCan("1")) {

            return Product::destroy($id);

        } else {
            return [
                'status' => 'Permission denied to create',
            ];
        }
    }
}
