<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\UserRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', []);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::guard()->user());
    }

    public function reports()
    {
        $reports = DB::table('reports')->select('day','movie_name','city')->get();

        // print_r($reports[0]); exit();
        foreach ($reports[0] as $key => $value) {
            if($key == 'day')
            {
                $arr = ['day' => $value];
            }
            if($key == 'movie_name')
            {
                $arr['movie_name'] = $value;
            }
            else
            {
                $encodedSku = json_encode($value);

                // foreach ($value as $key => $val) {
                    print_r($value);                 
                // } 
            }

        }exit();
        //print_r($arr);
        exit();
        return response()->json([
                'response' => $reports,
            ]);
       
    }

    public function upload(UserRequest $request){
        if($request->hasFile('Report-File')){
            $path = $request->file('Report-File')->getRealPath();
            $data = \Excel::load($path)->get();


            if($data->count()){
                foreach ($data as $key => $value) {
                
                //print_r($value); 
                    if(!empty($value[0]))
                    {
                        if($key == 0)
                        {
                            $arr = ['movie_name' => $value[1]];
                        }
                        if($key == 1)
                        {
                            $arr['day'] = $value[1];
                        }
                        if($key >= 4)
                        {
                            $arr[$value[0]] = $value;
                        }

                    }
                }
                //print_r($arr); exit();
                if(!empty($arr)){
                    \DB::table('reports')->insert($arr);
                    return response()->json([
                         'success' => 'Updated successfully',
                    ]);
                }
            }
        }
        return response()->json([
                'error' => 'File Does Not Exist',
            ]);
    } 

}
