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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
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
            if ($validator->fails()) {
              return response()->json([
                  'message' => 'Not authenticated',
              ], 400);
          }
        }

        $room = ConferenceRoom::create([
            'name' => $request->name,
            'join_code' => Str::random(8),
            'user_id' => $user->id
        ]);
        
        if ($room) {
            return response()->json([
                'message' => 'Room is created!'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Room can\'t be created. Something went wrong.',
            ], 400);
        }
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConferenceRoom  $conferenceRoom
     * @return \Illuminate\Http\Response
     */
    public function show(ConferenceRoom $conferenceRoom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ConferenceRoom  $conferenceRoom
     * @return \Illuminate\Http\Response
     */
    public function edit(ConferenceRoom $conferenceRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ConferenceRoom  $conferenceRoom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ConferenceRoom $conferenceRoom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConferenceRoom  $conferenceRoom
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConferenceRoom $conferenceRoom)
    {
        //
    }
}
