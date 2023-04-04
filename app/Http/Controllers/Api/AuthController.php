<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class AuthController extends Controller
{

    private function apiResponse($status, $message, $data = null, $statusCode = 200) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }


    public function register(Request $request) {

        $validator = validator()->make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:clients',
            'password' => 'required|confirmed',
            'phone' => 'required',
            'city_id' => 'required',
            'blood_type_id' => 'required',
            'd_o_b' => 'required',
            'last_donation_date' => 'required',
        ]);

        if($validator->fails()) {

            return $this->apiResponse(0, 'خطأ', $validator->errors(), 403);

        }

        $request['password'] = bcrypt($request->password);

        $client = Client::create($request->all());
        $client->api_token = Str::random(60);
        $client->save();

        return $this->apiResponse('1', 'تم الاضافة بنجاح', [
            'api_token' => $client->api_token,
            'client' => $client
        ]);

    }

    public function login(Request $request) {

        $validator = validator()->make($request->all(), [
            'password' => 'required',
            'phone' => 'required',
        ]);

        if($validator->fails()) {

            return $this->apiResponse(0, 'خطأ', $validator->errors(), 403);

        }

       $client = Client::where('phone', $request->phone)->first();

       if($client) {

            if(Hash::check($request->password, $client->password)) {

                return $this->apiResponse(1, 'تم تسجيل الدخول بنجاح', [

                    'api_token' => $client->api_token,
                    'client' => $client

                ]);

            } else {

                return $this->apiResponse(0, 'البيانات غير صحيحة');

            }


       } else {

            return $this->apiResponse(0, 'البيانات غير صحيحة');

       }


    }
}
