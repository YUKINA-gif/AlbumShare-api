<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class LikesController extends Controller
{
    public function post(Request $request)
    {
        $now = Carbon::now();
        $param = [
            "photo_id" => $request->photo_id,
            "user_id" => $request->user_id,
            "created_at" => $now,
            "updated_at" => $now
        ];
        DB::table("likes")->insert($param);
        return response()->json([
            "message" => "Like created successfully",
            "data" => $param
        ], 200);
    }
    public function delete(Request $request)
    {
        DB::table("likes")->where("photo_id", $request->photo_id)->where("user_id", $request->user_id)->delete();
        return response()->json([
            "message" => "Like deleted successfully"
        ], 200);
    }
}
