<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Item extends Model
{
  use SoftDeletes;
	protected $fillable=[
    'codeno', 'expired_date', 'deposit', 'amount', 'delivery_fees', 'other_fees', 'receiver_name', 'receiver_address', 'receiver_phone_no', 'remark', 'paystatus', 'client_id', 'township_id','staff_id','error_remark','sender_gate_id','sender_postoffice_id'
  ];

  public function pickup()
  {
    return $this->belongsTo('App\Pickup');
  }

  public function township()
  {
    return $this->belongsTo('App\Township');
  }

  public function way()
  {
    return $this->hasOne('App\Way');
  }

  public function staff()
  {
    return $this->belongsTo('App\Staff');
  }

  public function SenderGate(){
    return $this->belongsTo('App\SenderGate');
  }

  public function SenderPostoffice(){
    return $this->belongsTo('App\SenderPostoffice');
  }
}
