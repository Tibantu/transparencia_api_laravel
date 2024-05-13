<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class REDISController extends Controller
{
    //
    public function index($id) {

      //tr3at3ar 3a exc5eption do redi1s c5om fin3aly
      $cachedBlog = null;
      try{

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
            if($blog){
                $blogJson = json_encode($blog);
                Redis::set('user_' . $id, $blogJson);
              }
          }

    } catch (\Exception $e) {
      return response()->json([
          'status_code' => 500,
          'message' => 'Error occurred while interacting with Redis',
          'error' => $e->getMessage(),
      ]);
    }finally{
      if(!$cachedBlog) {
          $blog = User::find($id);
          return response()->json([
              'status_code' => 201,
              'message' => 'Fetched from database',
              'data' => $blog,
        ]);
      }
    }

  }
}
