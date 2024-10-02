<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    //use HasFactory;

    protected $fillable = [
        'project_id',
        'project_title', 
        'email_address', 
        'company_name',
        'name_of_bidder_and_or_authorized_representative', 
        'official_mobile_no',
        'linkfile',
        'status'
    ];


    public function bid() {
        return $this->belongsTo(ProjectList::class);
    }

}
