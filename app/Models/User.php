<?php

namespace App\Models;

use App\Enums\UserRoleEnums;
use App\Enums\UserStatusEnums;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = [
        'staff_id',
        'phone_no',
        'gender',
        'first_name',
        'last_name',
        'email',
        'password',
        'user_role',
        'user_status',
        'branch_id'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_role' => UserRoleEnums::class,
        'user_status' => UserStatusEnums::class
    ];

        public function branch()
        {
            return $this->hasOne(Branch::class, 'id','branch_id');
        }

        public function order()
        {
            return $this->belongsTo(Order::class, 'id', 'user_id',);
        }


        public static function adminRole(){
            return self::where('user_role', UserRoleEnums::Admin)->get();
        }


        public function getUserWithBranch($orderId){
          return  $this->with(['branch'])->whereHas('order', function (Builder $query) use ($orderId){
                                        $query->where('id', $orderId); })
                                        ->firstOrFail();
        }

        public function firstRecord($id)
        {
            return $this->where('id', $id)->first();
        }


        public function userBranch($authId)
        {
            return $this->where('id', $authId)->with('branch')->first();
        }

        public function Email($email)
        {
            return $this->where('email',$email)->first();
        }

}
