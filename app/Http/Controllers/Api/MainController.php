<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Post;
use App\Models\Token;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Category;
use App\Models\BloodType;
use App\Models\Governorate;
use App\traits\ApiResponse;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\DonationRequest;
use App\traits\notifyByFirebase;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\ContactRequest;
use App\Http\Requests\api\DonationRequestValidation;

// use function App\helpers\apiResponse;


class MainController extends Controller {
    use apiResponse;
    use notifyByFirebase;

    public function bloodTypes() {

        $blood_types = BloodType::paginate(10);

        return $this->apiResponse(1, 'success message', $blood_types);

    }// end of blood types

    public function governorates() {

        $governorates = Governorate::all();

        return $this->apiResponse(1, 'success', $governorates);

    }// end of governorates

    public function cities(Request $request) {

        $cities = City::where(function($query) use ($request) {

            if($request->has('governorate_id')) {

                return $query->where('governorate_id', $request->governorate_id);

            }

        })->get();

        return $this->apiResponse(1, 'success', $cities);

    }// end of cities

    public function categories(Request $request) {

        $categories = Category::paginate(5);

        return $this->apiResponse(1, 'success', $categories);

    }// end of categories

    public function posts(Request $request) {

        $posts = Post::with('category')->where(function($query) use ($request) {

            if($request->has('category_id')) {

                return $query->where('category_id', $request->category_id);

            }

        })->paginate(5);

        return $this->apiResponse(1, 'success', $posts);

    }// end of posts

    // public function createDonationRequest(DonationRequestValidation $request) {


    //     $validator = validator()->make($request->all(), $request->rules());

    //     if($validator->fails()) {

    //         return $this->apiResponse(0, $validator->errors()->first(), $validator->errors());

    //     }

    //     $donation_request = DonationRequest::create($request->all());

    //     return $this->apiResponse(1, 'success', $donation_request);

    // }// end of create donation request

     ////////////////////////////// start donationRequestCreate ///////////////
    public function createDonationRequest(Request $request){
    //validation
    $validator = validator()->make($request->all(), [

        'patient_name'         =>'required',
        'patient_age'          =>'required',
        'blood_type_id'        =>'required|exists:blood_types,id',
        'bags_nums'            =>'required',
        'hospital_name'        =>'required',
        'hospital_address'     =>'required',
        'latitude'             =>'nullable',
        'longitude'            =>'nullable',
        'city_id'              =>'required|exists:cities,id',
        'patient_phone'        =>'required',
        'details'              =>'required',

    ]);

    if($validator->fails()) {

        return $this->apiResponse(0, $validator->errors()->first(), $validator->errors());

    }

    // dd(CurrentAcccessToken());


    //create donationRequest
    $donationRequest = $request->user()->donationRequests()->create($request->all());

    // dd($donationRequest->city->governorate->clients);

    //find clients suitable for this donation request
    $clientsIds = $donationRequest->city->governorate->clients()
        ->whereHas('bloodType',function ($q) use($request, $donationRequest) {

            $q->where('blood_types.id', $request->blood_type_id);

        })->pluck('clients.id')->toArray();

        // dd(count($clientsIds));

        if (count($clientsIds)) {

            //create a notification on database
            $notification = $donationRequest->notifications()->create([

                'title'   => 'يوجد حالة تبرع قريبة منك',
                'content' => optional($donationRequest->blood_type)->name . 'محتاج تبرع لفصيلة'

            ]);

            // attach clients to this notification
            $notification->clients()->attach($clientsIds);

        }

        // dd($clientsIds->tokens());

        // get tokens
        $tokens = Token::where('client_id', $clientsIds)->where('token','!=', null)->pluck('token')->toArray();
        // $tokens = $clientsIds->currentAccessToken()->toArray();

        // dd($tokens);
        if (count($tokens)) {

            $title = $notification->title;
            $body = $notification->content;

        $data =[

            'donation_request_id' => $donationRequest->id

        ];

        $send = $this->notifyByFirebase($title, $body, $tokens, $data);

        // dd($send);

        }

        return $this->apiResponse(1, 'success', $donationRequest);


    }

 ////////////////////////////// end donationRequestCreate ///////////////

    public function donationRequests(Request $request) {

        $donation_requests = DonationRequest::with('bloodType', 'client', 'city')
            ->where(function($query) use ($request) {

                // if($request->has('blood_type_id') || $request->has('client_id') || $request->has('city_id') ) {

                //     return $query->where('blood_type_id', $request->blood_type_id)
                //         ->orWhere('client_id', $request->client_id)
                //         ->orWhere('city_id', $request->city_id);

                // }

                if($request->has('blood_type_id')) {

                    $query->where('blood_type_id', $request->blood_type_id);

                }
                if($request->has('client_id')) {

                    $query->where('client_id', $request->client_id);

                }
                if($request->has('city_id')) {

                    $query->where('city_id', $request->city_id);

                }

        })->paginate(5);

        return $this->apiResponse(1, 'success', $donation_requests);

    }// end of donation requests

    public function notifications(Request $request) {

        $notifications = $request->user()->notifications()->with('donationRequest')->paginate(5);

        return $this->apiResponse(1, 'success', $notifications);

    }// end of nitifications

    public function settings() {

        $settings = Setting::first();

        return $this->apiResponse(1, 'success', $settings);

    }// end of settings

    public function showPost(Post $post) {

        if($post) {

            return $this->apiResponse(1, 'success', $post);

        } else {

            return $this->apiResponse(0, 'No data found');

        }

    }// end of show post

    public function contacts(ContactRequest $request) {

        $validator = validator()->make($request->all(), $request->rules());
        // $validator = $request->validate($request->rules());

        if($validator->fails()) {

            return $this->apiResponse(0, $validator->errors()->first(), $validator->errors());

        };


        $contact = Contact::create($request->all());


        return $this->apiResponse(1, 'success', $contact);

    } // end of contacts

    public function toggleFavourite(Request $request) {

        $validator = validator()->make($request->all(), [

            'post_id' => 'required|exists:posts,id'

        ]);

        if($validator->fails()) {

            return $this->apiResponse(0, $validator->errors()->first(), $validator->errors());

        }

        $post = $request->user()->posts()->toggle($request->post_id);

        return $this->apiResponse(1, 'success', $post);

        }// end of toggle favorites


        public function allFavourites(Request $request) {

        $allFavourites = $request->user()->posts()->paginate(5);

        return $this->apiResponse(1, 'success', $allFavourites);

    }// end of all favorites










}
