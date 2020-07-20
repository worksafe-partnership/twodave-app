@extends('egl::layouts.base')

@section('content')
<section class="hero is-primary is-fullheight">
  <div class="hero-body">
    <div class="container">
        <div class="columns" style="text-align:left">
            <div class="column">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Login
                        </p>
                        <a class="card-header-icon">
                            <span class="site-logo" style="background-image:url('/logo.png');">
                            </span>
                        </a>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            <form class="form" role="form" method="POST" action="{{ url('/login') }}">
                                {!! csrf_field() !!}

                                <div class="field">
                                    <label class="label">E-Mail Address</label>
                                    <p class="control">
                                        <input type="email" class="input" name="email" value="{{ old('email') }}">
                                    </p>
                                </div>
                                <div class="field">
                                    <label class="label">Password</label>
                                    <p class="control">
                                        <input type="password" class="input" name="password">
                                    </p>
                                </div>
                                <div class="field">
                                    <p class="control">
                                        <label class="checkbox">
                                          <input type="checkbox" name="remember">
                                          Remember Me</a>
                                      </label>
                                    </p>
                                </div>
                                <div class="field is-grouped">
                                    <p class="control">
                                        <button type="submit" class="button is-primary">Login</button>
                                    </p>
                                    <p class="control">
                                        <a href="{{ url('/password/reset') }}" class="button is-link">Forgot Password?</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-three-quarters">
                <div class="card">
                    <header class="card-header">
                        <h1 class="card-header-title" style="font-size:3rem">
                            Welcome to www.2dave.site
                        </h1>
                    </header>
                    <div class="card-content" style="padding-bottom: 1rem">
                        <div class="content">
                            <h3>Online tools that help you to:</h3>
                            <h4 style="font-size:3rem; font-weight:bold; margin-bottom:0;">
                                <span style="color:#fb0001">D</span>eliver<br />
                                <span style="color:#ed7c31">A</span>ctive<br />
                                <span style="color:#fdc000">V</span>isible<br />
                                <span style="color:#23b050">E</span>xcellence ...on YOUR Sites!
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
  </div>
</section>



@endsection
