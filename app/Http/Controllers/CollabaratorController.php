<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Collabarator;
use App\Models\Note;
use App\Models\User;
use DB;

use App\Http\Requests\SendEmail;

/*
 * Collaborator Controller
 * Creates, Delete Collaboration to Note
 * Update Note by Collaboration User
*/


class CollabaratorController extends Controller
{
    
    /*
     * Add Collaborator to Note ID.
     * Input fields: Bearer Token, note_id, Collaborator email
     * 
    */
    public function addCollabatorByNoteId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'note_id' => 'required',
            'email' => 'required|string|email|max:100',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $currentUser = JWTAuth::parseToken()->authenticate();
        $note = $currentUser->notes()->find($request->input('note_id'));
        $user = User::where('email', $request->email)->first();

        if($currentUser)
        {
            if($note)
            {
                if($user)
                {
                    $string = Collabarator::select('id')->where([
                        ['note_id','=',$request->input('note_id')],
                        ['email','=',$request->input('email')]
                    ])->get();
                        
                    if($string != '[]')
                    {
                        return response()->json(['message' => 'Collabarater Already Created' ], 404); 
                    }

                    $collab = new Collabarator;
                    $collab->note_id = $request->get('note_id');
                    $collab->email = $request->get('email');

                    $note_data =  Note::select('title', 'description')->where([['id','=',$collab->note_id]])->get();


                    if($currentUser->collabarators()->save($collab))
                    {
                        $sendEmail = new SendEmail();
                        $sendEmail->sendMailCollabarator($currentUser->email, $collab->email, $note_data);
                        return response()->json([
                            'message' => 'Collabarator created Sucessfully'
                        ], 201);
                    }
                    else
                    {
                        return response()->json([
                            'message' => 'Could not add Collaborator'
                        ], 404);
                    } 
                }
                else
                {
                    return response()->json([
                        'message' => 'Given Email Not Registered'
                    ], 404);
                }
            }
            else
            {
                return response()->json([
                    'message' => 'Notes not found'
                ], 404);
            }
        }
        else
        {
            return response()->json([ 
                'message' => 'Invalid authorization token'
            ], 404);
        }

    }

    public function deleteCollabaratorNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'note_id' => 'required',
            'email' => 'required|string|email|max:100',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $currentUser = JWTAuth::parseToken()->authenticate();
        if($currentUser)
        {
           $id = $request->input('note_id');
           $email = $request->input('email');
           
           $string = Collabarator::select('id')->where([
            ['note_id','=',$id],
            ['email','=',$email]
            ])->get();
            
            if($string == '[]')
            {
                return response()->json(['message' => 'Collabarater Not created' ], 404); 
            }
            $collabDelete = DB::table('collabarators')->where('note_id', '=', $id)->where('email', '=', $email)->delete();

            if($collabDelete)
            {
                return response()->json(['message' => 'Collabarator deleted Sucessfully' ], 201);
            }
            return response()->json(['message' => 'Collabarator could not deleted' ], 201);      
        }
    }

    public function updateNoteByCollabarator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'note_id' => 'required',
            'title' => 'string|between:2,30',
            'description' => 'string|between:3,1000',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }
    
        $currentUser = JWTAuth::parseToken()->authenticate();
        if($currentUser)
        {
            $id = $request->input('note_id');
            $email = $currentUser->email;

            $string = Collabarator::select('id')->where([
                ['note_id','=',$id],
                ['email','=',$email]
            ])->get();
                
            if($string == '[]')
            {
                return response()->json(['message' => 'Collabarater Not created' ], 404); 
            }
                
            $user = Note::where('id', $request->note_id)
                    ->update(['title' => $request->title,'description'=>$request->description]);

            if($user)
            {
                return response()->json(['message' => 'Note updated Sucessfully' ], 201);
            }
            return response()->json(['message' => 'Note could not updated' ], 402);  
        }
        return response()->json(['message' => 'Invalid authorization token' ], 404);
    }

    public function getAllCollabarators()
    {
        $User = JWTAuth::parseToken()->authenticate();

        if ($User) 
        {
            $user = Collabarator::select('note_id', 'email')
                ->where([
                    ['user_id', '=', $User->id],
                ])
                ->get();
            if ($user=='[]'){
                return response()->json([
                    'message' => 'Collabarators not found'
                ], 404);
            }
            return
            response()->json([
                'message' => 'Fetched Collabarators Successfully',
                'Collaborator Mails' => $user
            ], 201);
            
        }
        return response()->json([
            'status' => 403, 
            'message' => 'Invalid token'
        ]);
    }
    
}