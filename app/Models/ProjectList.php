<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectList extends Model
{
    use HasFactory;


    protected $fillable = [
        'project_id',
        'project_title', 
        'abc', 
        'enduser',
        'submission_date', 
        'itb',
        'sbb',
        'status'
    ];

    public function projectlist()
    {
        return $this->hasMany(Bid::class);
    }


    protected static function boot()
   {
       parent::boot();

    }

    public static function generateAutoNumber()
    {
        // Get the last inserted ID
        $lastId = self::max('id');

        // Generate the new number
        return '' . str_pad(($lastId + 1), 1, '0', STR_PAD_LEFT);
    }
}
