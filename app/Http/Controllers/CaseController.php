<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;


use App\Models\Client;
use App\Models\CaseType;
use App\Models\CaseDetail;

use App\Helper;
use App\Mail\CaseMail;
use Mail;


/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */
class CaseController extends Controller
{
    public function addCaseType(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 400);
        }

        try {
            $insert = CaseType::create([
                'name' => $request->name
            ], 200);

            if($insert) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Case Type Added Successfully'
                ], 201);
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to Add Case Type'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function editCaseType(Request $request, $id) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 400);
        }

        try {
            $case = CaseType::find($id);

            if($case) {
                $case->name = $request->name;

                $case->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Case Updated Successfully'
                ], 201);
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Case Type does not exist'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->get->Message()
            ], 500);
        }
    }

    public function deleteCaseType(Request $request, $id) {
        try {
            $case = CaseType::find($id);

            if($case) {
                $case->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Case Deleetd Successfully'
                ], 201);
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Case Type does not exist'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->get->Message()
            ], 500);
        }
    }

    public function getAllCaseType(Request $request) {
        try {
            $case_types = CaseType::latest()->get();

            return response()->json([
                'status' => 'success',
                'message' => $case_types
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->get->Message()
            ], 500);
        }
    }

    public function getCasesByCaseType(Request $request, $id) {
        try {
            $cases = CaseDetail::where('case_type_id', $id)->latest()->get();

            return response()->json([
                'status' => 'success',
                'message' => $cases
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->get->Message()
            ], 500);
        }
    }

    public function addCaseDetails(Request $request) {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string',
            "date_of_birth" => 'required',
            'case_type_id' => 'required|int',
            'stage_of_case' => 'required|string',
            'act' => 'required|string',
            'filing_number' => 'required|string',
            'registration_date' => 'required',
            'first_hearing_date' => 'required',
            'priority' => 'required|int'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 400);
        }

        try {
            $client_id = null;
            //check if email address already existt
            $client = Client::where('email', $request->email)->first();

            if($client) {
                //get client id
                $client_id = $client->id;
            }
            else {
                //create the new client 
                $new_client = Client::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'date_of_birth' => $request->date_of_birth
                ]);

                $client_id = $new_client->id;
            }

            $case_number = "CASE" . Helper::generateCode(8);
            $registration_number = "REG" . Helper::generateCode(8);

            //insert the case details
            $insert_case = CaseDetail::create([
                'client_id' => $client_id,
                'case_number' => strtoupper($case_number),
                'case_type_id' => $request->case_type_id,
                'stage_of_case' => $request->stage_of_case,
                'act' => $request->act,
                'filing_number' => $request->filing_number,
                'registration_number' => strtoupper($registration_number),
                'registration_date' => $request->registration_date,
                'first_hearing_date' => $request->first_hearing_date,
                'priority' => $request->priority,
            ]);

            if($insert_case) {
                //send mail to client
                $details = [
                    'subject' => 'New Case Created',
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'case_number' => $case_number
                ];
        
                Mail::to($request->email)->send(new CaseMail($details));


                return response()->json([
                    'status' => 'success',
                    'message' => 'Case Submitted Successfully'
                ], 201);
            }

            return response()->json([
                'status' => 'failed',
                'Failed to Submit Case'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function editCaseDetails(Request $request, $id) {
        $validator = Validator::make($request->all(),[
            'case_type_id' => 'required|int',
            'stage_of_case' => 'required|string',
            'act' => 'required|string',
            'filing_number' => 'required|string',
            'registration_date' => 'required',
            'first_hearing_date' => 'required',
            'priority' => 'required|int'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 400);
        }
        
        try {
            $case = CaseDetail::find($id);

            if($case) {
                $case->case_type_id = $request->case_type_id;
                $case->stage_of_case = $request->stage_of_case;
                $case->act = $request->act;
                $case->filing_number = $request->filing_number;
                $case->registration_date = $request->registration_date;
                $case->first_hearing_date = $request->first_hearing_date;
                $case->priority = $request->priority;

                $case->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Case Updated Successfully'
                ], 201);
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Case does not exist'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteCaseDetails(Request $request, $id) {
        try {
            $case = CaseDetail::find($id);

            if($case) {
                $case->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Case Deleted Successfully'
                ], 201);
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Case does not exist'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCaseStage(Request $request, $id) {
        $validator = Validator::make($request->all(),[
            'stage_of_case' => 'required|string',
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 400);
        }
        
        try {
            $case = CaseDetail::find($id);

            if($case) {
                $case->stage_of_case = $request->stage_of_case;

                $case->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Stage of case updated'
                ], 201);
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Case does not exist'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllCase() {
        try {
            $cases = CaseDetail::with('client')->latest()->get();

            return response()->json([
                'status' => 'success',
                'message' => $cases
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
