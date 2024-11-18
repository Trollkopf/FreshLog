<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningLog extends Model
{
    protected $fillable = ['bathroom_id', 'user_id', 'cleaned_at'];
    protected $dates = ['cleaned_at'];
    
    public function user()
    {
        
        return $this->belongsTo(User::class);
    }
}
