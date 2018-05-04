@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">Create Question</div>
        <div class="card-body">
            @if($edit === FALSE)
                {!! Form::model($question, ['route' => ['questions.store'], 'method' => 'post']) !!}
            @else()
                {!! Form::model($question, ['route' => ['questions.update', $question->id], 'method' => 'patch']) !!}
            @endif
            <div class="form-group">
                {!! Form::label('body', 'Body') !!}
                {!! Form::text('body', $question->body, ['class' => 'form-control','required' => 'required']) !!}
            </div>
            <button class="btn btn-success float-right" value="submit" type="submit" id="submit">Save
            </button>
            {!! Form::close() !!}
        </div>

    </div>

@endsection