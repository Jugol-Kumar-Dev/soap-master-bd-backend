<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VerificationController;
use App\Mail\ForgatePasswordMail;
use App\Models\Lesson;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function loginStudent(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'failed',
                'message'=> 'Validation Error!',
                'error' => $validate->errors(),
            ], 403);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password) || $user->role == 'admin'){
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $user->tokens()->delete();
        $user->token = $user->createToken($request->email)->plainTextToken;

        $response = [
            'status' => 'success',
            'message' => 'User is logged in successfully!',
            'data' => $user,
        ];

        return response()->json($response, 200);
    }

    public function saveStudent(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'name' => 'nullable',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $url ="";
        if (\Illuminate\Support\Facades\Request::hasFile('certificate')) {
            $url = \Illuminate\Support\Facades\Request::file('certificate')->store('image', 'public');
        }

        $data['certificate'] = $url ?? null;
        $data['doctor_id'] = \Illuminate\Support\Facades\Request::input('doctorId');
        $data['is_active'] = 0;
        $user = User::create($data);


        if(env('MAIL_VERIFICATION')){
            $verification = new VerificationController();
            $verification->refendEmail($user->email);
        }

        return response()->json([
            "data" => $user,
        ]);
    }

    public function logoutStudent(Request $request): \Illuminate\Http\JsonResponse
    {

        $request->user()->tokens()->delete();
//        Auth::guard('web')->logout();
//        $request->session()->invalidate();
//        $request->session()->regenerateToken();
        return response()->json([
            'status' => 'success',
            'message' => 'logout successfully done.'
        ], 200);
    }

    public function sendForgotPasswordReqs(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')]
        ]);


        $user = User::where('email', \Illuminate\Support\Facades\Request::input('email'))->first();

        if($user != null){
            if ($user && $user != null){
                Mail::to($user)->send(new ForgatePasswordMail($user));
                return \response()->json('Resend Password Mail Send Successfully Done !', 200);
            }else{
                return \response()->json('Your Email Address Not Valid...!', 404);
            }
        }else{
            return \response()->json('Your Email Address Not Valid...!', 404);
        }

    }



    // student read this lesson if is login.
    public function readLesson($id)
    {
        $lesson = Lesson::findOrFail($id);
        return \response()->json($lesson);
    }


}
