<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questionoftheday extends Model
{
    protected $fillable=['question','option1','option1vote','option2','option2vote','option3','option3vote','option4','option4vote','option5','option5vote','status'];
}
