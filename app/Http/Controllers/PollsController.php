<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\Poll as PollResource;

use App\Poll;

class PollsController extends Controller
{
    public function index() 
    {
    	//$poll = Poll::get();
    	$poll = Poll::paginate(30);
    	return response()->json($poll, 200);
    }
    public function show($id)
    {
    	//$poll = Poll::find($id);
    	// if(is_null($poll)){
    	// 	return response()->json(null, 404);
    	// }
    	// // return response()->json(Poll::findorFail($id), 200);
    	$poll = Poll::with('questions')->findorFail($id); // this is for nesting data
    	$response['poll'] = $poll;
    	$response['questions'] = $poll->questions;
    	//$response = new PollResource($response, 200); this would work because it only accept laravel instance...but I send in an array instance of mine. This is for side loading
    	return response()->json($response, 200);
    }
    public function store(Request $request)
    {
    	$rules = [
    		'title' => 'required|max:250'
    	];
    	$validator = Validator::make($request->all(), $rules);

    	//dd($validator->fails());
    	if($validator->fails()) {
    		return response()->json($validator->errors(), 400);
    	}

    	$poll = Poll::create($request->all());
    	return response()->json($poll, 201);
    }
    public function update(Request $request, Poll $poll)
    {
    	$poll->update($request->all());
    	return response()->json($poll, 200);
    }
    public function delete(Request $request, Poll $poll)
    {
    	$poll->delete();
    	return response()->json(null, 204);
    }
    public function errors() 
    {
    	return response()->json(['msg' => 'Payment is required'], 501);
    }
    public function questions(Request $request, Poll $poll) 
    {
    	$questions = $poll->questions;
    	return response()->json($questions, 200);
    }
}
