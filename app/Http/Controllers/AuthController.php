<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use JWTAuth;
use JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator, DB, Hash, Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use \App\Service\UserService;

class AuthController extends Controller
{
    private $userService;

    public function __construct()
    {
        $this->userService  = new UserService();
    }

    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try
        {
            $credentials = $request->only('name', 'email', 'idRegistration', 'cpf');
        
            $rules = [
                'name' => 'required|max:255',
                'cpf' => 'required|max:11',
                'email' => 'required|email|max:255|unique:users'
            ];

            $validator = Validator::make($credentials, $rules);
            
            if($validator->fails()) {
                return response()->json([
                    'success'=> false,
                    'error'=> $validator->messages()
                ]);
            }

            \DB::beginTransaction();

            $user = $this->userService->createUser($request);

            if ($user)
            {
                if(strtoupper($request->get('type')) == "STUDENT")
                {
                    $complementUser = $this->userService->createStudent($request, $user->id);
                }
                else
                {
                    $complementUser = $this->userService->createTeacherOrAdm($request, $user->id);
                }
            }
            else 
            {
                DB::rollBack();
                return response()->json([
                    'success'=> false,
                    'error'=> "error"
                ]);
            }
            
            $name = $request->get('name');
            $email = $request->get('email');

            $verification_code = str_random(30); //Generate verification code

            DB::table('user_verifications')->insert(['user_id'=>$user->id, 'token'=>$verification_code]);

            $subject = "Please verify your email address.";

            Mail::send('email.verify', ['name' => $name, 'verification_code' => $verification_code],
                function($mail) use ($email, $name, $subject)
                {
                    $mail->from("noreplay@alocacaoalunostcc.com", "Alocação alunos TCC");
                    $mail->to($email, $name);
                    $mail->subject($subject);
                }
            );

            DB::commit();

            return response()->json([
                'success'=> true,
                'message'=> 'Thanks for signing up! Please check your email to complete your registration.'
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Verify User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($verification_code)
    {
        $check = DB::table('user_verifications')->where('token', $verification_code)->first();

        if(!is_null($check))
        {
            $user = User::find($check->user_id);

            if($user->is_verified == 1)
            {
                return response()->json([
                    'success'=> true,
                    'message'=> 'Account already verified..'
                ]);
            }

            $user->update(['is_verified' => 1]);

            DB::table('user_verifications')->where('token',$verification_code)->delete();

            return response()->json([
                'success'=> true,
                'message'=> 'You have successfully verified your email address.'
            ]);
        }
        return response()->json(['success'=> false, 'error'=> "Verification code is invalid."]);
    }

    /** 
     * API Login, on success return JWT Auth token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try
        {
            $credentials = $request->only('cpf', 'idRegistration');        
            $rules = [
                'cpf' => 'required',
                'idRegistration' => 'required',
            ];
            
            $validator = Validator::make($credentials, $rules);

            if($validator->fails())
            {
                return response()->json([
                    'success'=> false,
                    'error'=> $validator->messages()
                ], 401);
            }

            $user = User::where([
                    ['cpf', $request->get('cpf')],
                    ['idRegistration', $request->get('idRegistration')]
                ]
            )->first();
            
            if($user->is_verified == 0)
            {
                return response()->json(['success' => false, 'error' => 'User not verified.'], 404);
            }

            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::fromUser($user))
            {
                return response()->json([
                    'success' => false,
                    'error' => 'We cant find an account with this credentials.'
                ], 404);
            }
        }
        catch (JWTException $e)
        {
            // something went wrong whilst attempting to encode the token
            return response()->json([
                'success' => false,
                'error' => 'Failed to login, please try again.'
            ], 500);
        }

        // all good so return the token
        return response()->json([
            'success' => true, 
            'data'=> [
                'token' => $token
            ]
        ], 200);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->validate($request, ['token' => 'required']);
        
        try
        {
            JWTAuth::invalidate($request->input('token'));
            return response()->json([
                'success' => true,
                'message'=> "You have successfully logged out."
            ]);
        }
        catch (JWTException $e) 
        {
            // something went wrong whilst attempting to encode the token
            return response()->json([
                'success' => false,
                'error' => 'Failed to logout, please try again.'
            ], 500);
        }
    }
}
