<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use App\traits\ApiResponse;

class AuthController extends Controller
{

    use ApiResponse;


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

    }// end of register

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


    }// end of login

    public function resetPassword(Request $request) {

        $validator = validator()->make($request->all(), [
            'phone' => 'required'
        ]);

        if($validator->fails()) {

            return $this->apiResponse(0, $validator->errors());

        }

        $client = Client::where('phone', $request->phone);

        if($client) {

            $pin_code = rand(10000, 99999);

            $new_pin_code = $client->update(['pin_code' => $pin_code]);

            if($new_pin_code) {

                return $this->apiResponse(1, 'تم ارسال الكود', ['pin_code' => $pin_code]);

            } else {

                return $this->apiResponse(0, 'بياناتك غير صحيحة');

            }


        }else {

            return $this->apiResponse(0, 'بياناتك غير صحيحة');

        }

    }// end of reset password

    public function newPassword(Request $request) {

        $validator = validator()->make($request->all(), [
            'pin_code' => 'required',
            'password' => 'required|confirmed'
        ]);

        if($validator->fails()) {

            return $this->apiResponse(0, $validator->errors()->first(), $validator->errors());

        }

        $client = Client::where('pin_code', $request->pin_code)->first();

        if($client) {

            $client->update(['password' => bcrypt($request->password)]);

            return $this->apiResponse(1, 'تم تغيير كلمة السر بنجاح', ['new password ' => $client->password]);

        } else {

            return $this->apiResponse(0, 'بياناتك غير صحيحة');

        }



    }// end of new password
}
