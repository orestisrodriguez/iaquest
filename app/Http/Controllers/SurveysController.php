<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Survey;
use App\User;
use App\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class SurveysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::all();

        $surveys = $surveys->map(function ($survey, $key) {
            // add submissions count
            $submissions = Submission::where('survey_id', $survey->id)->get();

            $submitted = Submission::where('survey_id', $survey->id)->where('user_id', auth()->user()->id)->first();

            $survey->submissions = count($submissions);
            $survey->submitted = false;

            // add user data
            $user = User::find($survey->user_id);
            $survey->user = $user;
            unset($survey->user_id);

            //remove secret
            unset($survey->secret);

            if ($submitted) {
                $survey->submitted = true;
            }

            return $survey;
        });

        return response($surveys->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required', 'link' => 'required', 'subject' => 'required', 'secret' => 'required']);

        $user = User::find(auth()->user()->id);

        $surveys = $user->surveys()->get();
        $submissions = $user->submissions()->get();

        if (count($surveys) > 0) {
            if (count($submissions) / count($surveys) <= 5) {
                return response('Not enough submissions.', 422);
            }

        }

        $survey = new Survey;
        $survey->title = $request->title;
        $survey->link = $request->link;
        $survey->subject = $request->subject;
        $survey->secret = $request->secret;

        $survey->user()->associate($user);

        $survey->save();

        return response($survey->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     */
    public function update($id, Request $request)
    {
        
        $this->validate($request, ['title' => 'required', 'link' => 'required', 'subject' => 'required', 'secret' => 'required']);

        $survey = Survey::findOrFail($id);
        $survey->title = $request->title;
        $survey->link = $request->link;
        $survey->subject = $request->subject;
        $survey->secret = $request->secret;

        $survey->save();

        return response(null, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        Survey::destroy($id);

        return response(null, Response::HTTP_OK);
    }
}
