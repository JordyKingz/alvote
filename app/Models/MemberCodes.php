<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConferenceRoom;

class MemberCodes extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'code',
      'is_used',
    ];

    public function room(){
      return $this->belongsTo(ConferenceRoom::class);
    }
}
