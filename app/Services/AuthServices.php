<?php

namespace App\Services;

use App\Enums\UserStatusEnums;
use App\Helpers\GenerateRandomNumber;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Notifications\RegisteredUserNotification;
use App\Traits\ResponseTraits;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;


class AuthServices
{
    use ResponseTraits;
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(array $data)
    {
        $user = $this->user->Email($data['email']);
        if(!$user){
            return [
                'data' =>  null,
                'message' => 'User Doesnt Exist',
                'status' => false,
                'statusCode' => 200
            ];
        }
        if($user->user_status === UserStatusEnums::Deactivated) {
            return [
                'message' => 'User Account Deactivated',
                'data' => null,
                'status' => false,
                'statusCode' => 401
            ];
        }
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return [
                'message' => 'incorrect username or password',
                'data' => null,
                'statusCode' => 401,
                'status' => false,
            ];
        }
        $token = $user->createToken('apiToken')->plainTextToken;
        if (!$token) {
            return [
                'message' => 'Wrong User Email or Password',
                'statusCode' => 401,
               'status' => false,
                'data' => null,
            ];
        }
        $users = ['user' => $user, 'authorisation' => [ 'token' => $token,  'type' => 'bearer', ] ];
        return [
            'data' => $users,
            'message' => 'User Login SuccessFully',
            'status' => true,
            'statusCode' => 200
        ];
    }

    public function register(array $data): array
    {
          $password = $data['password'] ?? '123456';
          $data['staff_id'] = $data['staff_id'] ?? (new \App\Helpers\GenerateRandomNumber)->uniqueRandomNumber('UGL-STAFF-', 10);
         $data['password'] =  Hash::make($data['password'] ?? '123456');
         $user = User::create(['first_name' => $data['first_name'],
                                'last_name' => $data['last_name'],
                                'email' => $data['email'],
                                'phone_no' => $data['phone_no'],
                                'staff_id' => $data['staff_id'],
                                'gender' => $data['gender'],
                                'branch_id' => auth()->user()->branch_id,
                                'password' => $data['password'],
                                'user_role' => $data['role'],
                                'user_status' => UserStatusEnums::Activated
                            ]);

        Notification::send($user, new RegisteredUserNotification([  'user_name' => $user['first_name'] .' '. $user['last_name'],
                                                                    'user_id' => $user['id'],
                                                                    'email' => $user['email'],
                                                                    'staff_id' => $user['staff_id'],
                                                                    'password' => $password
                                                                  ]));


         return [
              'data' => new UserResource($user),
               'token' => $user->createToken("API TOKEN")->plainTextToken,
               'message' => 'User Registration Successfully',
               'status' => true,
               'statusCode' => 200
          ];
    }

    public function refresh()
    {
          $data =  [
            'user' => auth()->user(),
            'authorisation' => [ 'token' => auth()->refresh(), 'type' => 'bearer']
          ];
        return  [
            'data' => $data,
            'message' => 'User Registration Successfully',
            'statusCode' => 200,
            'status' => true
        ];
    }

    public function forgetPassword(array $data): array
    {
        $user = $this->user->where('email', '=', $data['email'])->first();
        if (!$user)
        {
            return [
                'message' => 'User does not exist',
                'status' => true,
                'statusCode' => 200,
                'data' => null
            ];
        }

        DB::table('password_resets')->insert(['email' => $data['email'],
                                                        'token' => str::random(5),
                                                        'created_at' => Carbon::now()
                                                    ]);

         $tokenData = DB::table('password_resets')
                        ->where('email', $data['email'])
                        ->first();

        if ($this->sendResetEmail($user, $data['email'], $tokenData->token))
        {
            return [
                    'data' => null,
                    'message' => 'A Password Reset Token Has Been Sent To Your Email Address.',
                    'statusCode' => 200,
                    'status' => true
            ];
        }

        return [
                    'message' => 'A Network Error occurred. Please try again.',
                    'statusCode' => 401,
                    'status' => true,
                    'data' => null
        ];
    }

    private function sendResetEmail(User $user, string $email, string $token)
    {
        $userData['first_name'] = $user['first_name'];
        $userData['last_name'] = $user['last_name'];
        $userData['email'] = $email;
        $userData['token'] = $token;
        $user->notify(new PasswordResetNotification($userData));
        try {
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function resetPassword(array $data): array
    {
       try {
               $tokenData = DB::table('password_resets')
                   ->where('token', $data['token'])
                   ->first();
               if (!$tokenData) {
                   return [
                       'message' => 'Invalid Token',
                       'data' => null,
                       'status' => false,
                       'statusCode' => 401
                   ];
               }
               $user = $this->user::where('email', $tokenData->email)->first();
               if (!$user) {
                   return ['message' => 'Email not found' , 'statusCode' => 401, 'data' => null];
               }
               $user->update(['password' => bcrypt($data['password'])]);
               DB::table('password_resets')->where('email', $user->email)->delete();
               return [
                   'data' => null,
                   'message' => "User Password Reset Successfully",
                   'statusCode' =>  200,
                   'status' => true
                ];
       }
       catch(Exception $exception)
       {
           return [
               'data' => $exception->getMessage(),
               'statusCode' => 401,
               'status' => true,
            ];
       }

    }

}
