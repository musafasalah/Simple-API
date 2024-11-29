<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function all()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if($category == null)
        {
            return response()->json([
                "msg"=>"Category Not Found"
            ],404);
        }

            return new CategoryResource($category);
    }

    public function store(Request $request)
    {
        //validation
        $Validator = Validator::make($request->all(),[
            'title'=>'required|string|max:100',
            'desc'=>'required|string',
            'image'=>'image|mimes:png,jpg,jpeg',
        ]);

        if($Validator->fails())
        {
            return response()->json([
                'msg'=>$Validator->errors()
            ],409);
        }


        //create
       $imagename = Storage::putfile("categories",$request->image);
       Category::create([
        "title"=>$request->title,
        "desc"=>$request->desc,
        "image"=>$request->imagename,
       ]);

        //msg
        return response()->json([
            "msg"=>"category created successfuly"
        ],201);
    }

    public function update(Request $request ,$id)
    {
              //validation
              $Validator = Validator::make($request->all(),[
                'title'=>'required|string|max:100',
                'desc'=>'required|string',
                'image'=>'image|mimes:png,jpg,jpeg',
            ]);

            if($Validator->fails())
            {
                return response()->json([
                    'msg'=>$Validator->errors()
                ],409);
            }

            //select
            $category = Category::find($id);
            if($category == null)
            {
                return response()->json([
                    "msg"=>"Category Not Found"
                ],404);
            }

            //update
            if($request->has('image'))
            {
                Storage::delete("$category->image");
               $imagename = Storage::putFile('categories',$request->image);
            }

            $category->update([
                "title"=>$request->title,
                "desc"=>$request->desc,
                "image"=>$imagename,
            ]);

             //msg
            return response()->json([
                "msg"=>"category updated successfuly",
                "category"=>new CategoryResource($category)
            ],201);

    }

     public function delete($id)
     {
        //select
        $category = Category::find($id);
        if($category == null)
        {
            return response()->json([
                "msg"=>"category not found"
            ],404);
        }

        //delete
        Storage::delete("$category->image");
        $category->delete();

        //msg
        return response()->json([
            "msg"=>"category deleted successfully"
        ],201);
     }

     public function search(Request $request , $title )
     {
        $search_value = Category::where('title',"LIKE",'%'.$title.'%')->get();
        return $search_value;

     }


}
