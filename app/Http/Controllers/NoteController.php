<?php

namespace App\Http\Controllers;
use App\Models\Note;
use App\Models\Label;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;
use Exception;
use Validator;
use DB;

use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    public function createNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:2,20',
            'description' => 'required|string|between:3,1000',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        try 
		{
            $note = new Note;
            $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->user_id = Auth::user()->id;
            $note->save();
        } 
		catch (Exception $e) 
		{
            return response()->json([
                'status' => 404, 
                'message' => 'Invalid authorization token'
            ], 404);
        }

        return response()->json([
		'status' => 201, 
		'message' => 'notes created successfully'
        ],201);
    }

    public function getAllNotes(Request $request)
    {

        $currentUser = JWTAuth::parseToken()->authenticate();

        if ($currentUser) 
        {

            $usernotes = Note::leftJoin('collabarators', 'collabarators.note_id', '=', 'notes.id')->leftJoin('labels', 'labels.note_id', '=', 'notes.id')
            ->select('notes.id','notes.title','notes.description','notes.pin','notes.archive','notes.colour','collabarators.email as Collabarator','labels.labelname')
            ->where('notes.pin','=','1')->where('notes.archive','=','0')
            ->where('notes.user_id','=',$currentUser->id)
            ->orWhere('collabarators.email','=',$currentUser->email)
            ->get();


            if (count($usernotes)==0){
                return response()->json([
                    'message' => 'Notes not found'
                ], 404);
            }
            return
            response()->json([
                'message' => 'Fetched Notes Successfully',
                'notes' => $usernotes
            ], 201);
        }
        return response()->json([
            'status' => 403, 
            'message' => 'Invalid token'
        ],403);
    }

    public function displayNoteById(Request $request)
    {      
        try
        {
            $note = new Note;
            $note->user_id = auth()->id();
            $id = $request->input('id');
            $User = JWTAuth::parseToken()->authenticate();
            $notes = $User->notes()->find($id);
            if ($notes==''){
                return response()->json([
                    'message' => 'Notes not found'
                ], 404);
            }
        }
        catch(Exception $e)
        {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        return $notes;
        
    }

    /**
     * Update Note by user particular id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNoteById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'string|between:2,30',
            'description' => 'string|between:3,1000',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }
        try
        {
            $id = $request->input('id');
            $currentUser = JWTAuth::parseToken()->authenticate();
            $note = $currentUser->notes()->find($id);
    
            if(!$note)
            {
                return response()->json([ 'message' => 'Notes not Found'], 404);
            }
    
            $note->fill($request->all());
    
            if($note->save())
            {
                return response()->json(['message' => 'Note updated Sucessfully' ], 201);
            }      
        }
        catch(Exception $e)
        {
            return response()->json(['message' => 'Invalid authorization token' ], 404);
        }
        return $note;
    }

    public function deleteNoteById(Request $request)
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
            $note = $currentUser->notes()->find($id);
    
            if(!$note)
            {
                return response()->json(['message' => 'Notes not Found'], 404);
            }
    
            if($note->delete())
            {
                return response()->json(['message' => 'Note deleted Sucessfully'], 201);
            }   
        }
        catch(Exception $e)
        {
            return response()->json(['message' => 'Invalid authorization token' ], 404);
        }
        
    }

    public function getAllNotesLabels()
    {
        $notes = new Note();
        $notes->user_id = auth()->id();

        if ($notes->user_id == auth()->id()) 
        {
            $usernotes = Note::select('id', 'title', 'description')
                ->where([
                    ['user_id', '=', $notes->user_id]
                ])
                ->get();
            if (count($usernotes)==0){
                return response()->json([
                    'message' => 'Notes not found'
                ], 404);
            }

            $arrayData = json_decode($usernotes);
            
            //$arrayData[0]-> labels -> getLabelsByNote($usernotes->id);


            foreach ($arrayData as $key => $value) {
                 $arrayData['Labels'] = $this->getLabelsByNote($value->id);

                 //$data[2]['image']="1280.jpg";
                // $temp[] = new data, 'Labels' = $this->getLabelsByNote($value->id) ;
                // if ($value['Code'] == '2') {
                //     $json_arr[$key]['Sports'] = "Foot Ball";
                // }
            }
            $newArrayData = json_encode($arrayData);
            // encode array to json and save to file
            //file_put_contents($Userlabels, json_encode($json_arr));

            return
            response()->json([
                'message' => 'Fetched Notes Successfully',
                'notes' => $newArrayData
            ], 201);
        }
        return response()->json([
            'status' => 403, 
            'message' => 'Invalid token'
        ],403);
    }

    public function getLabelsByNote($note_id)
    {
        $userLabels = Label::select('id', 'labelname')
            ->where([
                ['note_id', '=', $note_id]
            ])
            ->get();
        if (count($userLabels)==0){
            return response()->json([
                'message' => 'No Labels'
            ], 404);
        }
        return
        response()->json([
            'message' => 'Fetched Labels Successfully',
            'labels' => $userLabels
        ], 201);
        
    }

    public function pinNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $id = $request->input('id');
        $currentUser = JWTAuth::parseToken()->authenticate();
        $note = $currentUser->notes()->find($id);

        if(!$note)
        {
            Log::error('Notes Not Found',['user'=>$currentUser,'id'=>$request->id]);
            return response()->json(['message' => 'Notes not Found'], 404);
        }

        if($note->pin == 0)
        {
            Note::where('id', $request->id)->update(['pin' => 1]);

            Log::info('notes Pinned',['user_id'=>$currentUser,'note_id'=>$request->id]);
            return response()->json(['message' => 'Note Pinned Sucessfully' ], 201);
        }

        $user = Note::where('id', $request->id)
                         ->update(['pin' => 0]);

        Log::info('notes UnPinned',['user_id'=>$currentUser,'note_id'=>$request->id]);
        return response()->json(['message' => 'Note UnPinned ' ], 201);    
    }

    public function getAllPins()
    {
        $notes = new Note();
        $notes->user_id = auth()->id();

        if ($notes->user_id == auth()->id()) 
        {
            $usernotes = Note::select('id', 'title', 'description', 'pin', 'archive')
                ->where([
                    ['user_id', '=', $notes->user_id],['pin','=', 1]
                ])
                ->get();
            
            if (count($usernotes)==0){
                return response()->json([
                    'message' => 'Notes not found'
                ], 404);
            }
            return
            response()->json([
                'message' => 'Fetched Pinned Notes Successfully',
                'notes' => $usernotes
            ], 201);
        }
        return response()->json([
            'status' => 403, 
            'message' => 'Invalid token'
        ],403);
    }

    public function archiveNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $id = $request->input('id');
        $currentUser = JWTAuth::parseToken()->authenticate();
        $note = $currentUser->notes()->find($id);

        if(!$note)
        {
            Log::error('Notes Not Found',['user'=>$currentUser,'id'=>$request->id]);
            return response()->json([
                'message' => 'Note may be deleted or not yet created'
            ], 404);
        }

        if($note->archive == 0)
        {
            Note::where('id', $request->id)->update(['archive' => 1]);

            Log::info('notes Archived',['user_id'=>$currentUser,'note_id'=>$request->id]);
            return response()->json([
                'message' => 'Selected Note Archived' 
            ], 201);
        }

        Note::where('id', $request->id)->update(['archive' => 0]);

        Log::info('notes UnArchived',['user_id'=>$currentUser->emial,'note_id'=>$request->id]);
        return response()->json([
            'message' => 'Selected Note UnArchived ' 
        ], 201);    
    }

    public function getAllArchive()
    {
        $notes = new Note();
        $notes->user_id = auth()->id();

        if ($notes->user_id == auth()->id()) 
        {
            $usernotes = Note::select('id', 'title', 'description', 'archive', 'pin')
                ->where([
                    ['user_id', '=', $notes->user_id],['Archive','=', 1]
                ])
                ->get();
            
            if (count($usernotes)==0){
                return response()->json([
                    'message' => 'Note may be deleted or not yet created'
                ], 404);
            }
            return
            response()->json([
                'message' => 'Fetched Notes which are archived',
                'notes' => $usernotes
            ], 201);
        }
        return response()->json([
            'status' => 403, 
            'message' => 'Invalid Bearer token'
        ],403);
    }

    public function colourNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'colour'=>'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $id = $request->input('id');
        $currentUser = JWTAuth::parseToken()->authenticate();
        $note = $currentUser->notes()->find($id);

        if(!$note)
        {
            Log::error('Notes Not Found',['user'=>$currentUser,'id'=>$request->id]);
            return response()->json(['message' => 'Notes not Found'], 404);
        }

        $colours  =  array(
            'black'=>'rgb(0,0,0)', 'white'=>'rgb(255,255,255)', 'green'=>'rgb(0,255,0)',
            'red'=>'rgb(255,0,0)', 'blue'=>'rgb(0,0,255)', 'yellow'=>'rgb(255,255,0)',
            'grey'=>'rgb(128,128,128)', 'purple'=>'rgb(128,0,128)', 'brown'=>'rgb(165,42,42)',
            'orange'=>'rgb(255,165,0)', 'pink'=>'rgb(255,192,203)'
        );  
        
        $colour_name = strtolower($request->colour);

        if (isset($colours[$colour_name]))
        {
            $user = Note::where('id', $request->id)
                            ->update(['colour' => $colours[$colour_name]]);


            Log::info('notes coloured',['email'=>$currentUser->email,'note_id'=>$request->id]);
            return response()->json(['message' => 'Note colour changed to '.$colour_name ], 201);
        }
        else
        {
            return response()->json(['message' => 'colour Not Specified in the List' ], 400);
        }

    }

    public function getColouredNotes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'colour' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $colour_name = strtolower($request->input('colour'));

        $colours  =  array(
            'black'=>'rgb(0,0,0)', 'white'=>'rgb(255,255,255)', 'green'=>'rgb(0,255,0)',
            'red'=>'rgb(255,0,0)', 'blue'=>'rgb(0,0,255)', 'yellow'=>'rgb(255,255,0)',
            'grey'=>'rgb(128,128,128)', 'purple'=>'rgb(128,0,128)', 'brown'=>'rgb(165,42,42)',
            'orange'=>'rgb(255,165,0)', 'pink'=>'rgb(255,192,203)'
        );

        $notes = new Note();
        $notes->user_id = auth()->id();

        if (isset($colours[$colour_name]))
        {
            if ($notes->user_id == auth()->id()) 
            {
                $usernotes = Note::select('id', 'title', 'description')
                    ->where([
                        ['user_id', '=', $notes->user_id],
                        ['colour', '=', $colours[$colour_name]]
                    ])
                    ->get();
                
                if ($usernotes=='[]'){
                    return response()->json([
                        'message' => 'Notes not found'
                    ], 404);
                }
                return
                response()->json([
                    'message' => 'Fetched Notes of colour '.$colour_name,
                    'notes' => $usernotes
                ], 201);
            }
            return response()->json([
                'status' => 403, 
                'message' => 'Invalid token'
            ],403);
        }
        return response()->json(['message' => 'colour Not Specified in the List' ], 400);
    }

    public function getSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $string = $request->input('search');
        $currentUser = JWTAuth::parseToken()->authenticate();

        if ($currentUser) 
        {
            $usernotes = Note::leftJoin('collabarators', 'collabarators.note_id', '=', 'notes.id')->leftJoin('labels', 'labels.note_id', '=', 'notes.id')
            ->select('notes.id','notes.title','notes.description','notes.pin','notes.archive','notes.colour','collabarators.email as Collabarator','labels.labelname')
            ->where('notes.user_id','=',$currentUser->id)->Where('notes.title', 'like','%'.$string.'%')
            ->orWhere('notes.user_id','=',$currentUser->id)->Where('notes.description', 'like','%'.$string.'%')
            ->orWhere('notes.user_id','=',$currentUser->id)->Where('labels.labelname', 'like','%'.$string.'%')
            ->orWhere('collabarators.email','=',$currentUser->email)->Where('notes.title', 'like','%'.$string.'%')
            ->orWhere('collabarators.email','=',$currentUser->email)->Where('notes.description', 'like','%'.$string.'%')
            ->orWhere('collabarators.email','=',$currentUser->email)->Where('labels.labelname', 'like','%'.$string.'%')
            ->get();

            if (count($usernotes)==0){
                return response()->json([
                    'message' => 'No result Found'
                ], 404);
            }
            
            return
            response()->json([
                'message' => 'Fetched Notes Successfully',
                'notes' => $usernotes
            ], 201);
        }
        return response()->json([
            'status' => 403, 
            'message' => 'Invalid token'
        ],403);
    }

    public function getpaginateNoteData()
    {
        $noteData = Note::paginate(3);
        return response()->json([
            'message' => 'Paginate Notes',
            'notes' => $noteData
        ], 201);
    }
}