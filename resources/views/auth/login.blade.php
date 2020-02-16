@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container">
      <h1 class="title">{{ __('Login') }}</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field">
              <label class="label" for="email">{{ __('E-Mail Address') }}</label>
              <div class="control @error('email') is-danger @enderror">
                <input id="email" type="email" class="input" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
              </div>

                @error('email')
                    <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>


            <div class="field">
              <label class="label" for="password">{{ __('Password') }}</label>
              <div class="control @error('password') is-danger @enderror">
                <input id="password" type="password" class="input" name="password" required autocomplete="current-password">
              </div>

                @error('password')
                    <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <label class="checkbox">
              <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              {{ __('Remember Me') }}
            </label>
            
            <div class="content is-vcentered has-text-centered">
                <input class="button" type="submit" value="{{ __('Login') }}">
                &nbsp;&nbsp;
                @if (Route::has('password.request'))
                    <a class="is-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</section>
@endsection
