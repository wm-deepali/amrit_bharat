<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class CloseAccountRequest extends Model
{
    protected $fillable=['user_id','reason_id','detail','file'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function reason()
    {
        return $this->belongsTo('App\Reason');
    }


    public static function saveData($record)
    {
        $saveData = new CloseAccountRequest;
        $saveData->user_id = $record->user_id;
        $saveData->reason_id = $record->reason;
        $saveData->detail = $record->detail;
        $saveData->file =  $record->image;
        
        $saveData->save();
        return $saveData->id;
    }
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
