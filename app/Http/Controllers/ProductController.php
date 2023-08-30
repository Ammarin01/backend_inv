<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Image;

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
        // return Product::all();

        //อ่านข้อมูลแบบแบ่งหน้า
        return Product::orderBy('id','desc')->paginate(25);
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
            $data_product = array(
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'price' => $request->input('price'),
                'description' => $request->input('description'),
                'user_id' =>$user->id,
            );
            //รับไฟล์ภาพ
            $image = $request->file();

            //เช็คว่าผู้ใช้มีการอัพโหลดเข้ามาหรือไม่
            if (!empty($image)) {
                # code...
                $file_name = "product_".time().".".$image->getClientOriginalExtension();

                //กำหนดขนาดไฟล์
                $imgWidth = 400;
                $imgHeight = 400;
                $folderupload = public_path('images/products/thumbnail');
                $path = $folderupload."/".$file_name;

                //อัพโหลด เข้าสู่ folder  thumbnail
                $img = Image::make($image->getRealPath());
                $img->orientate()->fit($imgHeight,$imgWidth, function($constraint){
                    $constraint->upsize();
                });
                $img->save($path);

                //อัพโหลดภาพต้นฉบับ folder original
                $destinationPath = public_path('images/products/original');
                $image->move($destinationPath,$file_name);

                //กำหนด path รูปเพื่อใส่ตารางข้อมูล
                $data_product['image'] = url('/').'images/products/thumbnail/'.$file_name;
            }else{
                $data_product['image'] = url('/').'images/products/thumbnail/no_img.png';
            }
            
            // return response($data_product, 201);
            // return response($request->all(), 201);
            // return Product::create($request->all());
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
