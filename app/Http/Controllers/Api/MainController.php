<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Post;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Category;
use App\Models\BloodType;
use App\Models\Governorate;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\DonationRequest;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    private function apiResponse($status, $message, $data = null, $statusCode = 200) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function bloodTypes() {

        $blood_types = BloodType::paginate(10);

        return $this->apiResponse(1, 'success message', $blood_types);



    }


    public function governorates() {

        $governorates = Governorate::all();

        return $this->apiResponse(1, 'success', $governorates);

    }

    public function cities(Request $request) {

        $cities = City::where(function($query) use ($request) {

            if($request->has('governorate_id')) {

                return $query->where('governorate_id', $request->governorate_id);

            }

        })->get();

        return $this->apiResponse(1, 'success', $cities);

    }

    public function categories(Request $request) {

        $categories = Category::all();

        return $this->apiResponse(1, 'success', $categories);

    }

    public function posts(Request $request) {

        $posts = Post::with('category')->where(function($query) use ($request) {

            if($request->has('category_id')) {

                return $query->where('category_id', $request->category_id);

            }

        })->get();

        return $this->apiResponse(1, 'success', $posts);

    }

    public function createDonationRequest(Request $request) {

        $validator = validator()->make($request->all(), [
            'patient_name' => 'required',
            'patient_phone' => 'required',
            'patient_age' => 'required|integer',
            'hospital_name' => 'required',
            'hospital_address' => 'required',
            'bags_nums' => 'required|integer',
            'details' => 'required',
            'city_id' => 'required|exists:cities,id',
            'client_id' => 'required|exists:clients,id',
            'blood_type_id' => 'required|exists:blood_types,id',
        ]);

        if($validator->fails()) {

            return $this->apiResponse(0, $validator->errors()->first(), $validator->errors());

        }

        $donation_request = DonationRequest::create($request->all());

        return $this->apiResponse(1, 'success', $donation_request);



    }

    public function donationRequests(Request $request) {

        $donation_requests = DonationRequest::with('bloodType', 'client', 'city')
            ->where(function($query) use ($request) {

                if($request->has('blood_type_id') || $request->has('client_id') || $request->has('city_id') ) {

                    return $query->where('blood_type_id', $request->blood_type_id)
                        ->orWhere('client_id', $request->client_id)
                        ->orWhere('city_id', $request->city_id);

                }

        })->get();

        return $this->apiResponse(1, 'success', $donation_requests);

    }

    public function notifications() {

        $notifications = Notification::with('donationRequest')->get();

        return $this->apiResponse(1, 'success', $notifications);

    }

    public function settings() {

        $settings = Setting::all();

        return $this->apiResponse(1, 'success', $settings);

    }

    public function showPost(Post $post) {

        return $this->apiResponse(1, 'success', $post);

    }

    public function contacts() {

        $contacts = Contact::all();

        return $this->apiResponse(1, 'success', $contacts);

    }


}
