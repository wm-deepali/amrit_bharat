<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use DateTimeInterface;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'contact', 'country_code', 'dob', 'username', 'password', 'gender', 'address', 'state_id', 'city_id', 'image', 'cv', 'role', 'permission', 'user_number', 'added_by', 'status', 'account_status', 'google_id', 'fcm_token', 'delete_status','delete_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany('App\Post')->where('user_delete_status', '0');
    }

    public function userComment()
    {
        return $this->hasMany('App\PostComment');
    }

    public function userLike()
    {
        return $this->hasMany('App\PostBookmarkLike')->where('likes', '1');
    }

    public function approvedposts()
    {
        return $this->posts()->where('status','published');
    }

    public function state()
    {
        return $this->belongsTo('App\State');
    }
    
    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public static function saveData($record, $id = null)
    {
        $saveData = ($id) ? User::find($id) : new User;
        $saveData->name = $record->name;
        $saveData->email = $record->email;
        $saveData->contact = $record->contact;
        $saveData->country_code = $record->country_code;
        $saveData->username = $record->username;
        $saveData->dob = $record->dob;
        if($id == null):
            $saveData->password = Hash::make($record->password);   
            $saveData->status =  'approved';
            $saveData->added_by =  'frontend';
            $saveData->user_number =  $record->user_number;
            $saveData->image =  $record->image;
            $saveData->fcm_token =  $record->fcm_token;
            
        endif;
        $saveData->gender =  $record->gender;
        $saveData->address =  $record->address;
        $saveData->state_id =  $record->state;
        $saveData->city_id =  $record->city;
        $saveData->role =  $record->role;
        
        
        $saveData->save();
        return $saveData->id;
    }

    public static function saveGoogleData($record)
    {
        $saveData = new User;
        $saveData->name = $record->name;
        $saveData->email = $record->email;
        $saveData->google_id = $record->google_id;
        $saveData->password = Hash::make('abc@123');
        $saveData->role =  $record->role;
        $saveData->status =  'approved';
        $saveData->added_by =  'frontend';    
        $saveData->user_number =  $record->user_number;  
        $saveData->save();
        return $saveData->id;
    }
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    
}
