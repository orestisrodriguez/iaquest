<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Survey;
use App\User;
use Illuminate\Http\Request;
use Session;

class SurveysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::all();

        return $surveys;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required', 'link' => 'required', 'user_id' => 'required', 'secret' => 'required']);

        $survey = new Survey;
        $survey->title = $request->title;
        $survey->link = $request->link;
        $survey->subject = $request->subject;
        $survey->secret = $request->secret;

        $survey->user()->associate(User::find($request->user_id)->first());

        $survey->save();

        return response([], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     */
    public function update($id, Request $request)
    {
        
        $this->validate($request, ['title' => 'required', 'link' => 'required']);

        $data = $request->all();
        
        $survey = Survey::findOrFail($id);
        $survey->update($data);

        return response([], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        Survey::destroy($id);

        return response([], 204);
    }
}
