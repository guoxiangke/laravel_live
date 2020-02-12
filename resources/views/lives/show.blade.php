@extends('layouts.app')

@section('content')
<div class="container">
    <live :user="{{ $user }}" :live="{{ $live }}"></live>
</div>
@endsection