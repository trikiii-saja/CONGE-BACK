<?php

namespace App\Http\Controllers\Api\V1;
use App\Filters\V1\UsersFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\V1\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\VerificationMailer;
use App\Mail\ResetPasswordMailer;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filter = new UsersFilter();
        $queryItems = $filter->transform($request); //[['column, 'operator', 'value']]
        $users = User::where($queryItems);
        $userCollection = new UserCollection($users->paginate()->appends($request->query()));
        return response()->json([
            'data' => $userCollection,
            'status' => 'success',
        ], 200);
    }
    public function show(User $user)
    {
        $userData = new UserResource($user);
        return response()->json([
            'data' => $userData,
            'status' => 'success',
        ], 200);
    }
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'bio' => $request->bio,
                'job_code' => $request->job_code,
            ]);

            $verificationCode = $this->sendEmail($request->email, 0);
            $user->update(['verification_code' => $verificationCode]);

            return response()->json([
                'data' => new UserResource($user),
                'status' => 'success',
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    protected function createUser(array $data)
    {
        return User::create($data);
    }
    protected function updateVerificationCode(User $user, $verificationCode)
    {
        $user->update(['verification_code' => $verificationCode]);
    }
public function login(Request $request)
{
    try {
        $validator = $this->validateLoginRequest($request);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 400);
        }

        // Retrieve the user by email
        $user = $this->getUserByEmail($request->email);
        
        // Check if user exists
        if (!$user) {
            return $this->errorResponse('Invalid email or password', 401);
        }

        // Check if the provided password matches the hashed password in the database
        if (!Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Invalid email or password', 401);
        }

        // If password matches, login successful
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => new UserResource($user),
        ], 200);
    } catch (\Exception $e) {
        return $this->errorResponse($e->getMessage());
    }
}

    public function checkEmail(Request $request)
    {
        try {
            $validator = $this->validateEmailCheckRequest($request);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->first(), 400);
            }
            $user = $this->getUserByEmail($request->email);
            if ($user) {
                $verificationCode = $this->sendEmail($request->email,1);
                $this->updateVerificationCode($user, $verificationCode);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Email already exists. Verification code sent successfully.',
                ], 200);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Email does not exist',
            ], 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
    public function verifyCode(Request $request)
{
    try {
        // Find the user by email and verification code
        $user = User::where('email', $request->email)
                     ->where('verification_code', $request->verification_code)
                     ->first();

        // If no user found, return an error response
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid verification code or email',
            ], 404);
        }

        // Update the user's verification status
        $user->update(['verified' => 1]);

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Email verified successfully.',
        ], 200);
    } catch (\Exception $e) {
        // Handle any unexpected exceptions
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while verifying the code.',
        ], 500);
    }
}
    protected function errorResponse($message, $statusCode = 500)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $statusCode);
    }
    protected function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }
    protected function sendEmail($email,$type)
    {
        $verificationCode = $this->generateVerificationCode();
        if($type==0){
            Mail::to($email)->send(new VerificationMailer($verificationCode));
        }else{
            Mail::to($email)->send(new ResetPasswordMailer($verificationCode));
        }
        
        return $verificationCode;
    }
    protected function generateVerificationCode()
    {
        return \Illuminate\Support\Str::random(6);
    }
    protected function validateLoginRequest($request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
    protected function getUserByEmailAndPassword($email, $password)
    {
        return User::where('email', $email)->where('password', $password)->first();
    }
    protected function validateEmailCheckRequest($request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
    }
    protected function validateVerifyCodeRequest($request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|email',
            'verification_code' => 'required|string|min:6|max:6',
        ]);
    }
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:8',
                'verification_code' => 'required|string|min:6|max:6',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->first(), 400);
            }
            $user = User::where('email', $request->email)
                        ->where('verification_code', $request->verification_code)
                        ->first();
            if (!$user) {
                return $this->errorResponse('Invalid email or verification code', 404);
            }
            $user->update([
                'password' => Hash::make($request->password),
                'verification_code' => null,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully',
            ], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
