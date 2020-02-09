@extends('layouts.app')

@section('content')
<div class="container">
    <live :user="{{ auth()->user() }}" :live="{{ $live }}"></live>
</div>
@endsection