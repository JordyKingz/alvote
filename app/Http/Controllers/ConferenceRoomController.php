<?php

namespace App\Http\Controllers;

use App\Models\ConferenceRoom;
use App\Models\Association;
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

            return response()->json([
                'room' => $room,
                'association' => $association,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No rooms found',
            ], 404);
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
              'message' => 'Something went wrong setting room to open',
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
              'message' => 'Something went wrong closing the room',
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
