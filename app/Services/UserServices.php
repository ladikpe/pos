<?php

namespace App\Services;

use App\Enums\UserRoleEnums;
use App\Enums\UserStatusEnums;
use App\Helpers\CheckingIdHelpers;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Traits\ResponseTraits;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserServices
{
    use ResponseTraits;
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(array $data)
    {
        $user = CheckingIdHelpers::checkAuthUserBranch($this->user);
        $user =  $user->where('user_role', $data['user_role']);

        if(isset($data['user_status']))
        {
            $users = $user->where('user_status' , 'Like', '%' . $data['user_status']. '%');
        }
       elseif(isset($data['name']))
       {
           $search = $data['name'];
           $userRole = $data['user_role'];
          $users = $user->where( function($query) use ($search, $userRole) {
                       $query->where('user_role', $userRole)->where( function ($query) use ($search) {
                           $query->OrWhere('first_name' , 'Like', '%' . $search. '%')
                               ->OrWhere('last_name' , 'Like', '%' . $search. '%')
                               ->orWhere(DB::raw('CONCAT(first_name," ",last_name)'),'like','%'.$search.'%');
                       });
          });
       }
       elseif(isset($data['staff_id']))
       {
           $users = $user->where('staff_id' , 'Like', '%' . $data['staff_id'] . '%');
       }
        elseif (isset($data['email']))
        {
            $users = $user->where('email' , 'Like', '%' . $data['email'] . '%');
        }
        else{
            $users = $user;
        }

        return [
            'data' => $users->with('branch')->orderByDesc('id')->paginate(10),
            'message' => 'All User Details Retrieved SuccessFully',
            'status' => true,
            'statusCode' => 200,
        ];
    }

    public function updateProfile(array $data): array
    {
        $id = $data['id'];
        $user = $this->user->userBranch($id);
        if(!$user){
            return [
                'data' =>  null,
                'message' => 'User Not Found',
                'statusCode' => 401,
                'status' => false
            ];
        }
        $userData = [
            'first_name' => $data['first_name'] ?? $user->first_name,
            'last_name' => $data['last_name'] ?? $user->last_name,
            'gender' => $data['gender']  ?? $user->gender,
            'branch_id' => $data['branch_id'] ?? $user->branch_id,
            'phone_no' => $data['phone_no'] ?? $user->phone_no,
        ];
        $user->update($userData);
        return [
            'data' => $user,
            'message' => 'User Detail Updated Successfully',
            'status' => false,
            'statusCode' => 200,
        ];
    }

    public function changePassword(array $data): array
    {
        if(!Hash::check($data['current_password'], auth()->user()->password))
        {
           return [
               'message' => "Old Password Doesn't match!",
               'data' => null,
               'status' => false,
               'statusCode' => 401
           ];
        }

        $new_password = bcrypt($data['new_password']);
        if(Hash::check($data['current_password'], $new_password))
        {
            return [
                'data' => null,
            'message' => "Current Password Cant Be Your New Password",
            'status' => false,
            'statusCode' => 401,
        ];
        }

        $user =  $this->user::whereId(auth()->user()->id)->first();
        $user->update(['password' => Hash::make($data['new_password'])]);
        return [
            'data' => new UserResource($user),
            'message' => "Password Change SuccessFully",
            'status' => true,
            'statusCode' => 200,
        ];

    }

    public function showUser(array $data): array
    {
        $user = $this->user->userBranch($data['id']);
        if(!$user)
        {
            return [
                'data' => null,
            'message' => 'User Doesnt Exist',
            'status' => false,
            'statusCode' => 401,
            ];
        }
        return [
            'data' => $user,
            'message' => 'Single User Detail Selected Successfully',
            'status' => true,
            'statusCode' => 200,
        ];
    }

    public function authenticatedUser(): array
    {
        $user = $this->user->userBranch(auth()->user()->id);
        return [
            'status' => true,
            'message' => 'Logged In User Data Selected',
            'data' => $user,
            'statusCode' =>  200
        ];
    }

    public function changeStatus(array $data): array
    {
        $user =  $this->user->firstRecord($data['id']);
        if(!$user)
        {
            return [
            'data' => null,
            'message' => 'User Doesnt Exist',
            'status' => false,
            'statusCode' => 401,
           ];
        }

        if($user->user_status === UserStatusEnums::Activated)
        {
            $user->update(['user_status' => UserStatusEnums::Deactivated]);
            return [
            'data' => new UserResource($user),
            'message' => "User Deactivated Successful",
            'status' => true,
            'statusCode' => 200,
          ];
        }

        $user->update(['user_status' => UserStatusEnums::Activated]);
        return [
            'data' => new UserResource($user),
            'message' => "User Activated Successful",
            'status' => truw,
            'statusCode' => 200,
        ];
    }

    public function changeUserRole(array $data): array
    {
        $user =  $this->user->firstRecord($data['id']);
        if(!$user)
        {
            return [
                'data' => null,
            'message' => 'User Doesnt Exist',
            'status' => false,
            'statusCode' => 401,
        ];
        }

        if($user->user_role === UserRoleEnums::Admin)
        {
            $user->update(['user_role' => UserRoleEnums::Cashier]);
            return [
            'data' => new UserResource($user),
            'message' => "User Deactivated Successful",
            'status' => true,
            'statusCode' => 200,
           ];
        }

        $user->update(['user_role' => UserRoleEnums::Admin]);
        return [
            'data' => new UserResource($user),
            'message' => "User Activated Successful",
            'status' => true,
            'statusCode' => 200,
        ];
    }

    public function updateUserProfile(array $data): array
    {
        $user =  $this->user->userBranch(auth()->user()->id);
        if(!$user)
        {
            return [
           'data' => null,
            'message' => 'User Doesnt Exist',
            'status' => false,
            'statusCode' => 401,
        ];
        }
        $user->update($data);
        return [
            'data' => $user,
            'message' => "User Successful",
            'status' => true,
            'statusCode' => 200,
        ];
    }

    public function delete($id)
    {
     try {
         $user = $this->user->firstRecord($id);
         $user->delete();
         return [
             'data' => null,
             'message' => 'User Deleted Successfully',
             'status' => true,
             'statusCode' => 200
         ];
     }
        catch(Exception $exception){
            return $exception;
        }
    }

}
