<?php

namespace App\Http\Controllers;

use App\Models\ConferenceRoom;
use App\Models\Association;
use App\Models\MemberCodes;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Str;


class ConferenceRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $user = Auth::user();

        if ($user === null) {
          return response()->json([
              'message' => 'Not authenticated',
          ], 400);
        }

        $rooms = ConferenceRoom::where('user_id', $user->id)->get();
        
        if ($rooms != null) {
            $association = Association::find($user->association_id);

            return response()->json([
                'rooms' => $rooms,
                'association' => $association,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No rooms found',
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        $user = Auth::user();

        if ($user === null) {
          return response()->json([
              'message' => 'Not authenticated',
          ], 400);
        }
        
        try {
            $room = ConferenceRoom::create([
                'name' => $request->name,
                'join_code' => Str::random(8),
                'user_id' => $user->id,
                'status' => 0,
            ]);
            
            if ($room != null) {
                return response()->json([
                    'message' => 'Room is created!',
                    'room' => $room
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Room can\'t be created. Something went wrong.',
                ], 400);
            }
        } catch(Exception $e) {
            return response()->json([
              'message' => 'Something went wrong setting creating the room',
            ], 400);
        }
    } 

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConferenceRoom  $conferenceRoom
     * @return \Illuminate\Http\Response
     */
    public function show(ConferenceRoom $conferenceRoom, $id)
    {
        $user = Auth::user();

        if ($user === null) {
            if ($validator->fails()) {
              return response()->json([
                  'message' => 'Not authenticated',
              ], 400);
          }
        }

        $room = ConferenceRoom::find($id);

        if ($room != null) {
            $association = Association::find($user->association_id);
            $votes = Vote::where('room_id', $room->id)->get();

            return response()->json([
                'room' => $room,
                'association' => $association,
                'votes' => $votes,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No rooms found',
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function join(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'roomCode' => ['required'],
            'personalCode' => ['required']
        ]);
        
        // check validator
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        // check the codes
        $memberCode = MemberCodes::where('code', $request->personalCode)->first();

        if ($memberCode->is_used) {
            return response()->json([
              'message' => 'This code already has been used.',
            ], 400);
        }

        $room = ConferenceRoom::where('join_code', $request->roomCode)->first();

        if ($memberCode == null || $room == null) {
            return response()->json([
              'message' => 'One of your codes is invalid.',
            ], 400);
        }

        // check if room is open
        // status = 0 created
        // status = 1 open
        // status = 2 closed
        if ($room->status != 1) {
            return response()->json([
              'message' => 'Room is closed. Wait for your host to open the room.',
            ], 400);
        } 

        // Set code to used
        try {
            $memberCode->is_used = true;
            $memberCode->save();
        } catch (Exception $e) {
            return response()->json([
              'message' => 'Something went wrong joining the room: '. $e,
            ], 400);
        }

        try {
            $room->members_joined++;
            $room->save();

            // TODO:
            // member has joined the room. 
            // it would be awesome if the admin
            // get an automatic refresh through pusher

            return response()->json([
              'room' => $room,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
              'message' => 'Something went wrong joining the room: '. $e,
            ], 400);
        }
    } 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function open(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        $user = Auth::user();

        if ($user === null) {
            return response()->json([
                'message' => 'Not authenticated',
            ], 400);
        }

        $room = ConferenceRoom::find($request->id);

        if ($room === null) {
            return response()->json([
                'message' => 'Room not found',
            ], 404);
        }

        try {
          $room->status = 1;
          $room->save();

          $association = Association::find($user->association_id);

          return response()->json([
              'room' => $room,
              'association' => $association,
          ], 200);
        } catch(Exception $e) {
            return response()->json([
              'message' => 'Something went wrong setting room to open: '. $e,
          ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request, ConferenceRoom $conferenceRoom)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        $user = Auth::user();

        if ($user === null) {
            return response()->json([
                'message' => 'Not authenticated',
            ], 400);
        }

        $room = ConferenceRoom::find($request->id);

        if ($room === null) {
            return response()->json([
                'message' => 'Room not found',
            ], 404);
        }

        try {
          $room->status = 2;
          $room->save();

          $association = Association::find($user->association_id);

          return response()->json([
              'room' => $room,
              'association' => $association,
          ], 200);
        } catch(Exception $e) {
            return response()->json([
              'message' => 'Something went wrong closing the room: '. $e,
          ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConferenceRoom  $conferenceRoom
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConferenceRoom $conferenceRoom, $id)
    {
        $room = ConferenceRoom::destroy($id);

        if ($room) {
            return response()->json(200);
        } else {
            return response()->json([
                'message' => 'Failed to delete room. Try again.',
            ], 400);
        }
    }
}
