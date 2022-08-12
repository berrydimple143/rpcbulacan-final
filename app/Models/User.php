<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    
    protected $fillable = [
        'first_name',
        'last_name',
        'fb_url',
        'id_number',
        'province',
        'municipality',
        'barangay',
        'province_name',
        'municipality_name',
        'barangay_name',
        'district_no',
        'password',
        'email',
        'email_verified_at',
        'mobile',
        'status',
        'birth_year',
        'gender',
        'deleted',
        'deleted_by',
        'date_deleted',
        'id_released',
        'username',
        'valid_id_presented',
        'released_by',
        'date_released',
    ];
    
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];
    
    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')                
                ->orWhere('first_name', 'like', '%'.$search.'%')
                ->orWhere('last_name', 'like', '%'.$search.'%')            
                ->orWhere('fb_url', 'like', '%'.$search.'%')
                ->orWhere('id_number', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%')  
                ->orWhere('province_name', 'like', '%'.$search.'%')
                ->orWhere('municipality_name', 'like', '%'.$search.'%')  
                ->orWhere('barangay_name', 'like', '%'.$search.'%')
                ->orWhere('province', 'like', '%'.$search.'%')
                ->orWhere('municipality', 'like', '%'.$search.'%')  
                ->orWhere('barangay', 'like', '%'.$search.'%')
                ->orWhere('created_at', 'like', '%'.$search.'%');
    }
}
