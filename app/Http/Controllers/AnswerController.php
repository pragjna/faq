<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Question;
use App\Answer;
use Illuminate\Support\Facades\Gate;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($question)
    {
        $answer = new Answer();
        $edit = FALSE;
        return view('answerForm', ['answer' => $answer, 'edit' => $edit, 'question' => $question]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $question)
    {
        $input = $request->validate([
            'body' => 'required|min:5',
        ], [
            'body.required' => 'Body is required',
            'body.min' => 'Body must be at least 5 characters',
        ]);
        $input = request()->all();
        $question = Question::find($question);
        $Answer = new Answer($input);
        $Answer->user()->associate(Auth::user());
        $Answer->question()->associate($question);
        $Answer->save();
        return redirect()->route('questions.show', ['question_id' => $question->id])->with('message', 'Saved');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($question, $answer)
    {
        $answer = Answer::find($answer);
        return view('answer')->with(['answer' => $answer, 'question' => $question]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($question, $answer_id)
    {
        $answer = Answer::find($answer_id);
        //echo $answer;
        if (Gate::allows('editDeleteAnswers-auth', $answer)) {
            $edit = true;
            return view('answerForm', ['answer' => $answer, 'edit' => $edit, 'question' => $question]);
        }
       else (Gate::denies('editDeleteAnswers-auth', $answer)) ;
        {
            return redirect()->route('home')->with('message', 'Access Denied');

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $question, $answer)
    {
        $input = $request->validate([
            'body' => 'required|min:5',
        ], [
            'body.required' => 'Body is required',
            'body.min' => 'Body must be at least 5 characters',
        ]);

        $answer = Answer::find($answer);
        $answer->body = $request->body;
        $answer->save();
        return redirect()->route('answers.show', ['question_id' => $question, 'answer_id' => $answer])->with('message', 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($question, $answer_id)
    {
        $answer = Answer::find($answer_id);
        if (Gate::allows('editDeleteAnswers-auth', $answer)) {
            $answer->delete();
            return redirect()->route('questions.show', ['question_id' => $question])->with('message', 'Delete');
        }

        else (Gate::denies('editDeleteAnswers-auth', $answer)) ;
        {
            return redirect()->route('home')->with('message', 'Access Denied');

        }
    }

}



