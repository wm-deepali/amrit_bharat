<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    protected $fillable=['user_id','subject','details','file'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public static function saveData($record)
    {
        $saveData = new Help;
        $saveData->user_id = $record->user_id;
        $saveData->subject = $record->subject;
        $saveData->details = $record->details;
        $saveData->file =  $record->document;
        
        $saveData->save();
        return $saveData->id;
    }
}
