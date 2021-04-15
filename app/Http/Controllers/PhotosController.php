<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Photo::all();
        return response()->json([
            "message" => "OK",
            "data" => $items
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = new Photo;

        $this->validate($request, [
            'file' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        if (request()->file) {
            $image = $request->file('file');
            $path = Storage::disk('s3')->put('/', $image, 'public');

            
            $item->file = Storage::disk('s3')->url($path);
            $item->user_id = $request->user_id;
            $item->save();

            return response()->json([
                "message" => "Photo posted successfully",
                "data" => $item
            ],200);
        }
        }

    /**
     * Display the specified resource.
     *
     * @param  Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show($photo)
    {
        $item = Photo::where("id",$photo)->first();
        $comments = DB::table("comments")->where("photo_id",$photo)->get();
        $comment_data = array();
        $user = DB::table("users")->where("id",$photo)->first();
        $user_id = $item->user_id;
        $user = DB::table("users")->where("id", (int)$user_id)->first();
        $like = DB::table("likes")->where("photo_id",$photo)->get();

        foreach ($comments as $value) {
            $comment_user = DB::table("users")->where("id",$value->user_id)->first();
            $comments = [
                "comment_user" => $comment_user,
                "comment" => $value,
            ];
            array_push($comment_data,$comments);
        }
            $items = [
                "data" => $item, 
                "comment" => $comment_data,
                "like" => $like,
                "user_id" => $user_id,
                "user" => $user
            ];
            return response()->json($items,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        //
    }
}
