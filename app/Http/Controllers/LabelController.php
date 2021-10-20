<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use JWTAuth;
use Auth;
use Exception;
use Validator;


class LabelController extends Controller
{
    public function createLabel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'labelname' => 'required|string|between:2,30',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $currentUser = JWTAuth::parseToken()->authenticate();
        if ($currentUser)
        {
            $labelName = Label::where('labelname', $request->labelname)->first();
            if ($labelName)
            {
                Log::alert('Existing Label Name given to add',['Email'=>$request->email]);
                return response()->json(['message' => 'Given LabelName already exists'],401);
            }
        
            $label = new Label;
            $label->labelname = $request->get('labelname');
            if($currentUser->labels()->save($label))
            {
                return response()->json(['message' => 'Label added Sucessfully'], 201);
            }
            return response()->json(['message' => 'Label not added'], 405);
        }
        return response()->json(['message' => 'Invalid authorization token'], 404);
    }

    public function addLabel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'note_id' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $currentUser = JWTAuth::parseToken()->authenticate();
        
        if($currentUser)
        {
            $id = $request->input('id');
            $note_id = $request->input('note_id');
            
            $label = $currentUser->labels()->find($id);
    
            if(!$label)
            {
                return response()->json([ 'message' => 'Label not Found'], 404);
            }

            $note = $currentUser->notes()->find($note_id);
    
            if(!$note)
            {
                return response()->json([ 'message' => 'Notes not Found'], 404);
            }

            $label->note_id = $request->get('note_id');
            
            if($currentUser->labels()->save($label))
            {
                return response()->json([ 'message' => 'Label Added to Note Sucessfully' ], 201);
            }
            return response()->json([ 'message' => 'Label Did Not added to Note' ], 403);

        }
        return response()->json([ 'message' => 'Invalid authorization token'], 404);
    }

    public function displayLabelById(Request $request)
    {      
        try
        {
            $label = new Label;
            $label->user_id = auth()->id();
            $id = $request->input('id');
            $User = JWTAuth::parseToken()->authenticate();
            $labels = $User->labels()->find($id);
            if ($labels==''){
                return response()->json([
                    'message' => 'labels not found'
                ], 404);
            }
        }
        catch(Exception $e)
        {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        return $labels;
        
    }

    public function updateLabelById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'labelname' => 'required|string|between:2,20',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }
        try
        {
            $id = $request->input('id');
            $currentUser = JWTAuth::parseToken()->authenticate();
            $label = $currentUser->labels()->find($id);
    
            if(!$label)
            {
                return response()->json([ 'message' => 'Label not Found'], 404);
            }
    
            $label->fill($request->all());
    
            if($label->save())
            {
                return response()->json(['message' => 'Label updated Sucessfully' ], 201);
            }      
        }
        catch(Exception $e)
        {
            return response()->json(['message' => 'Invalid authorization token' ], 404);
        }
        return $label;
    }

    public function deleteLabelById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }
        try
        {
            $id = $request->input('id');
            $currentUser = JWTAuth::parseToken()->authenticate();
            $label = $currentUser->labels()->find($id);
    
            if(!$label)
            {
                return response()->json(['message' => 'Label not Found'], 404);
            }
    
            if($label->delete())
            {
                return response()->json(['message' => 'Label deleted Sucessfully'], 201);
            }   
        }
        catch(Exception $e)
        {
            return response()->json(['message' => 'Invalid authorization token' ], 404);
        }
        
    }

    public function getAllLabels()
    {
        //$labels = new Label();
        //$labels->user_id = auth()->id();
        $User = JWTAuth::parseToken()->authenticate();

        if ($User) 
        {
            $user = Label::select('id', 'labelname')
                ->where([
                    ['user_id', '=', $User->id],
                ])
                ->get();
            if ($user=='[]'){
                return response()->json([
                    'message' => 'Labels not found'
                ], 404);
            }
            return
            response()->json([
                'message' => 'Fetched Labels Successfully',
                'Labels' => $user
            ], 201);
            
        }
        return response()->json([
            'status' => 403, 
            'message' => 'Invalid token'
        ]);
    }

    public function getLabelsByNote(Request $request)
    {
        //$labels = new Label();
        //$labels->user_id = auth()->id();
        $User = JWTAuth::parseToken()->authenticate();

        //$note_id = $request->input('note_id');

        if ($User) 
        {
            $userLabels = Label::select('id', 'labelname')
                ->where([
                    ['note_id', '=', $request->input('note_id')]
                ])
                ->get();
            if ($userLabels=='[]'){
                return response()->json([
                    'message' => 'No Labels'
                ], 404);
            }
            return
            response()->json([
                'message' => 'Fetched Labels Successfully',
                'Labels' => $userLabels
            ], 201);
        }
        return response()->json([
            'status' => 403, 
            'message' => 'Invalid token'
        ]);
    }
}