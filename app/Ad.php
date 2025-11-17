<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable=['name','contact','email','address','state_id','city_id','pincode','title','type','page','position','image','link','code','startdate','enddate','amount','paymentmethod','remark','paymentdocument','collectedby','chequedate','chequenumber','bankname','bankbranch','utrnumber','utrdate','upiid','upidate','upireferencenumber','wallet','walletdate','walletreferencenumber','status','transactionnumber','paymentstatus'];

    public function categories()
    {
        return $this->hasMany('App\Adcategory');
    }
}
