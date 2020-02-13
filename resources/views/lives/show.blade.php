@extends('layouts.app')

@section('content')
<div class="container">
    <live :user="{{ $user }}" :live="{{ $live }}" :viewed="{{ $viewed }}"></live>
</div>
@endsection