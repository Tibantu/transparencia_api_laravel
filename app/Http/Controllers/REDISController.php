<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class REDISController extends Controller
{
    //
    public function index($id) {

      $cachedBlog = Redis::get('user_' . $id);
      //dd($cachedBlog);
      if($cachedBlog) {

        $blog = json_decode($cachedBlog, FALSE);
        return response()->json([
            'status_code' => 201,
            'message' => 'Fetched from redis',
            'data' => $blog,
        ]);

      }else {
        $blog = User::find($id);
        $blogJson = json_encode($blog);
        Redis::set('user_' . $id, $blogJson);
        return response()->json([
            'status_code' => 201,
            'message' => 'Fetched from database',
            'data' => $blog,
        ]);
      }
    }
}
