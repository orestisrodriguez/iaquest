<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Survey;

use App\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class SubmissionsController extends Controller
{

    public function store(Request $request)
    {
        $survey = Survey::findOrFail($request->survey_id);

        $this->validate($request, ['survey_id' => 'required', 'secret' => 'required']);

        if ($request->secret !== $survey->secret) {
            return response('Secret keyword incorrect.', 422);
        }

        $user = User::findOrFail(auth()->user()->id);
        
        if (Submission::where('survey_id', $survey->id)->where('user_id', $user->id)->first()) {
            return response('Already submitted.', 422);
        }

        $submission = new Submission;

        $submission->user()->associate($user);
        $submission->survey()->associate($survey);

        $submission->save();

        return response($submission->jsonSerialize(), Response::HTTP_CREATED);
    }
}
