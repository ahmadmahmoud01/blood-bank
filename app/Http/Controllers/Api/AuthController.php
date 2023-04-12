<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Mail\ResetPassword;
use App\traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Validator;

use function App\helpers\apiResponse;
use App\Http\Requests\api\RegisterRequest;
use App\Http\Requests\api\UpdateNotificationRequest;

class AuthController extends Controller {

    use ApiResponse;


    public function register(RegisterRequest $request) {

        $validator = validator()->make($request->all(), $request->rules());

        if($validator->fails()) {

            return $this->apiResponse(0, 'خطأ', $validator->errors(), 422);

        }

        $request['password'] = bcrypt($request->password);

        $client = Client::create($request->all());
        $token = $client->createToken('myapptoken')->plainTextToken;

        $client->pin_code = null;


        return $this->apiResponse('1', 'تم الاضافة بنجاح', [
            'token' =>$token,
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

                    'token' => $client->createToken('myapptoken')->plainTextToken,
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

        $validator = validator()->make($request->all(), ['phone' => 'required']);

        if($validator->fails()) {

            return $this->apiResponse(0, $validator->errors());

        }

        $client = Client::where('phone', $request->phone)->first();

        // dd($client);

        if($client) {

            $code = rand(1111,9999);

            $client->pin_code = $code;
            $client->save();
            // update(['pin_code' => $code]);
            // dd($client);


            if($client->save() == true) {
                Mail::to("ahmadelmehdawe@gmail.com")
                    // ->bcc($client->email)
                    ->send(new ResetPassword($code));

                return apiResponse(1, 'برجاء فحص الحساب', ['pin_code_for_reset' => $code]);

            } else {

                return apiResponse(0, 'حدث خطأ حاول مرة أخرى');

            }

        }else {
            return apiResponse(0, 'لايوجد حساب لهذا الرقم');
        }

    }// end of reset password

    public function newPassword(Request $request) {

        $validator = validator()->make($request->all(), [
            'pin_code' => 'required',
            'phone' => 'required',
            'password' => 'required|confirmed'
        ]);

        if($validator->fails()) {

            return $this->apiResponse(0, $validator->errors()->first(), $validator->errors());

        }

        $client = Client::where([
                ['pin_code' , $request->pin_code],
                ['phone', $request->phone]
            ])->first();

        if($client) {

            $client->update(['password' => bcrypt($request->password)]);

            return $this->apiResponse(1, 'تم تغيير كلمة السر بنجاح', ['new password ' => $client->password]);

        } else {

            return $this->apiResponse(0, 'بياناتك غير صحيحة');

        }

    }// end of new password

    public function logout(Request $request) {

        $request->user()->CurrentAccessToken()->delete();

        return ['message' => 'logged out'];

    }// end of logout

    public function updateProfile(Client $client, Request $request) {

        $client = tap($client)->update($request->all());

        return $this->apiResponse(1, 'success', $client->fresh());

    } // end of update profile

    public function updateNotificationSettings (UpdateNotificationRequest $request) {

        $validator = validator()->make($request->all(), $request->rules());

        if($validator->fails()) {

          return apiResponse(0, $validator->errors()->first(), $validator->errors());

        }

        $request->user()->bloods()->sync($request->blood_types);
        $request->user()->governorates()->sync($request->governorates);

        return apiResponse(1,'success');

    }

    public function getNotificationSettings (Request $request) {

        $data = [

            'blood_types' => $request->user()->bloods()->pluck('blood_types.id')->toArray(),
            'governorates' => $request->user()->governorates()->pluck('governorates.id')->toArray(),

        ];

        return $this->apoiResponse(1,'success',$data);
    }


}
