<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\PasswordReset;
use App\Http\Requests\SendEmailRequest;
use Exception;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
//use Validator;
use Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @since 
 * 
 * This is the main controller that is responsible for user registration,login,user-profile 
 * refresh and logout API's.
 */
class AuthController extends Controller
{
    
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,20',
            'lastname' => 'required|string|between:2,20',
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        if($validator->fails())
        {
            Log::warning('Given Invalid Credentials for register');
            return response()->json($validator->errors()->toJson(), 400);
        }

        $value = Cache::remember('users', 1, function () {
            return User::all();
        });

        $user = User::where('email', $request->email)->first();
        
        if ($user)
        {
            Log::alert('Existing Mail given for Register',['Email'=>$request->email]);

            //throw new RepeatedMailException();

            return response()->json([
                'message' => 'The email has already been taken'
            ],401);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        Log::info('New user Regitered',['Email'=>$request->email]);
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

     /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) 
        {
            Log::warning('Given Invalid Credentials to login');
            return response()->json($validator->errors(), 422);
        }

        //$user = User::where('email', $request->email)->first();

        // if(!$user)
        // {
        //     Log::alert('Unregistered Mail given for Login',['Email'=>$request->email]);
        //     return response()->json([
        //         'message' => 'we can not find the user with that e-mail address'
        //     ], 401);
        // }

        //  try 
        // {
            $user = User::where('email', $request->email)->first(); 
            
            if(!$user)
            {
                throw new NotFoundHttpException();
            }            
        // }
        // catch (NotFoundHttpException $ex)
        // {
        //     return $ex->getMessage();
        // }

        if (!$token = auth()->attempt($validator->validated()))
        {
            Log::alert('Wrong Password given for Login',['Email'=>$request->email]);
            return response()->json(['error' => 'Incorrect Password'], 404);
        }

        Log::info('User Logged in',['Email'=>$request->email]);

        $value = Cache::remember('users', 1, function () {
            return User::all();
        });

        return response()->json([ 
            'message' => 'Login successfull',  
            'access_token' => $token
        ],200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() 
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() 
    {
        try{
        if(auth()->logout())
        {
            return response()->json([
                'message' => 'User successfully signed out'
            ],201);
        }
        } 
        catch (Exception $e) 
		{
            return response()->json([
                'message' => 'Invalid authorization token'
            ], 404);
        }
        
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
        ]);
    }
}