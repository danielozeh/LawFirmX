<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;


use App\Models\Client;
use App\Models\CaseDetail;
use App\Helper;

use App\Mail\submitPassportMail;
use Mail;


/**
 * @author Daniel Ozeh
 */
class ClientController extends Controller
{
    public function getAllClients(Request $request) {
        try {
            $clients = Client::latest()->get();

            return response()->json([
                'status' => 'success',
                'message' => [
                    'clients' => $clients,
                    'image_path' => Helper::imagePath(). '/storage/app/public/clients/'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getClientInfo(Request $request, $id) {
        try {
            //find client
            $is_exist = Client::find($id);

            //check if client exist on our database
            if($is_exist) {
                //get total client cases
                $case_count = CaseDetail::where('client_id', $id)->count();

                return response()->json([
                    'status' => 'success',
                    'message' => [
                        'profile' => $is_exist,
                        'case_count' => $case_count,
                        'image_path' => Helper::imagePath(). '/storage/app/public/clients/'
                    ]
                ], 200);
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Client does not exist'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getClientCases(Request $request, $id) {
        try {
            //find client
            $is_exist = Client::find($id);

            //check if client exist on our database
            if($is_exist) {
                $cases = CaseDetail::where('client_id', $id)->latest()->get();

                return response()->json([
                    'status' => 'success',
                    'message' => $cases
                ], 200);
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Client does not exist'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchByLastName(Request $request) {
        $validator = Validator::make($request->all(),[
            'last_name' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 400);
        }

        try {
            $clients = Client::where('last_name', $request->last_name)->latest()->get();

            if(count($clients) < 1) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'There are no client with that last name'
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => [
                    'clients' => $clients,
                    'image_path' => Helper::imagePath() . '/storage/app/public/clients/'
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'failed' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfilePicture(Request $request, $id) {
        $validator = Validator::make($request->all(),[
            'profile_picture' => 'required|mimes:png,jpg|max:2048'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 400);
        }

        try {
            $client = Client::find($id);

            if($client) {
                if($profile_picture = $request->file('profile_picture')) {
                    $save_image = $request->profile_picture->store('public/clients');
                    $size = $request->file('profile_picture')->getSize();

                    $profile_picture = $profile_picture->hashName();
                }

                $client->profile_picture = $profile_picture;

                $client->save();

                return response()->json([
                    'status' => 'success', 
                    'message' => 'Profile Picture Updated'
                ], 201);  
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Client Does not exist'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'failed' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }      
    }

    public function notifyClientWithNullProfile() {
        //get all the users whose profile picture is null
        $all_clients = Client::where('profile_picture', null)->orWhere('profile_picture', "")->get();

        //get todays date.
        $today = Carbon::now();

        foreach ($all_clients as $client) {
            $last_email_date = Carbon::parse($client->last_date_email_sent);

            //return $last_email_date;

            $date_difference = $last_email_date->diffInDays($today);

            //if client last date email sent - today is 3 days
            if($date_difference == 3) {
                //send email to client
                $details = [
                    'subject' => 'Passport Submission Reminder',
                    'first_name' => $client->first_name,
                    'last_name' => $client->last_name,
                ];
        
                Mail::to($client->email)->send(new submitPassportMail($details));

                //update last date email sent to today
                $update_client = Client::find($client->id);
                $update_client->last_date_email_sent = $today;
                $update_client->save();
            }
        }
    }
}
