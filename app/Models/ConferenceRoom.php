<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Events\MemberJoinedRoom;

class ConferenceRoom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
      protected $fillable = [
        'name',
        'join_code',
        'user_id',
        'status',
        'invitations_send',
        'members_joined'
      ];

      // protected $dispatchesEvents = [
      //   'updated' => MemberJoinedRoom::class,
      // ];

      public function members() {
        return $this->hasMany(MemberCodes::class);
      }
}
