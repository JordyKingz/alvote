<?php

namespace App\Http\Controllers;

use App\Models\ConferenceRoom;
use App\Models\Vote;
use App\Models\Answer;
use App\Models\Association;
use App\Models\MemberCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Str;

class VoteController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'answers' => ['required'],
            'roomId' => ['required']
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
          /* TODO
           * Check room_id parameter if authenticated user created that room
           */

            // Create vote
            $vote = Vote::create([
              'name' => $request->name,
              'room_id' => $request->roomId,
            ]);
            
            // Create answers
            $answers = json_decode($request->answers, true);
            foreach ($answers as $answer) {
              Answer::create([
                'answer' => $answer['answer'],
                'vote_id' => $vote->id,
              ]);
            }

            if ($vote != null) {
                return response()->json([
                    'message' => 'Vote is created!',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Vote can\'t be created. Something went wrong.',
                ], 400);
            }
        } catch(Exception $e) {
            return response()->json([
              'message' => 'Something went wrong setting creating the vote',
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
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
