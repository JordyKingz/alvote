<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'answer',
      'vote_id',
    ];

    public function vote() {
      return $this->belongsTo(Vote::class, 'vote_id');
    }

    public function memberVotes() {
      return $this->hasMany(MemberVote::class);
    }
}
