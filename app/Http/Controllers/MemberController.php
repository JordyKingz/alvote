<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\ConferenceRoom;
use App\Models\MemberCodes;
use App\Models\Association;
use App\Models\Invitation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'max:255'],
            'roomId' => ['required']
        ]);
        
        // check validator
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        // Create new code. The member can use this
        // for loggin into the application
        $memberCode = MemberCodes::create([
            'code' => Str::random(8),
        ]);

        $room = ConferenceRoom::find($request->roomId);

        if ($room == null) {
            return response()->json([
              'message' => 'No room found. Try again.',
            ], 404);
        }

        try {
            // create invitation
            $invite = new Invitation();
            $invite->email = $request->email;
            $invite->room_code = $room->join_code;
            $invite->personal_code = $memberCode->code;
            
            // send notification email
            $invite->notify(new \App\Notifications\RoomInvitation($invite));

            try {
                $room->invitations_send++;
                $room->save();

                return response()->json([
                    'room' => $room,
                ], 200);
            } catch (Exception $e) {
                    return response()->json([
                    'message' => 'Something went wrong increment room invitations: '.$e,
                  ], 400);
            }
        } catch(Exception $e) {
            return response()->json([
              'message' => 'Something went wrong inviting member: '.$e,
            ], 400);
        }
    } 

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        //
    }
}
