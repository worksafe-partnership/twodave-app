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
                            <span class="site-logo" style="background-image:url('/2DAVE.beta.jpg');">
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
                                        <button type="submit" class="button is-success">Login</button>
                                    </p>
                                    <p class="control">
                                        <a href="{{ url('/password/reset') }}" class="button is-danger">Forgot Password?</a>
                                    </p>
                                </div>
                                <div class="field is-grouped">
                                    <p class="control">
                                        <button type="button" class="button is-warning" id="signup-button">Not a Member? Sign Up here</button>
                                    </p>
                                </div>
                            </form>
                            <div id="signup-message" style="display: none;">
                                <br/>
                                <p>
                                    <b>To sign up, please call or email:</b>
                                </p>
                                <p>
                                    Telephone: <b>0333 772 9195</b>
                                </p>
                                <p>
                                    E-mail: <b><a href="mailto:help@2dave.site">help@2dave.site</a></b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-three-quarters">
                <div class="card" style="font-family: Arial Black,Arial Bold,Gadget,sans-serif;">
                    <header class="card-header">
                        <h1 class="card-header-title title is-1">
                            <span style="color:#203878">Welcome to 2DAVE</span>
                        </h1>
                        <h2 class="card-header-title title is-3">
                            <span style="color:#203878">{{ env("APP_DESC") }}</span>
                        </h2>
                    </header>
                    <div class="card-content" style="padding-bottom: 4rem">
                        <div class="content">
                            <h3>Online tools that help you to:</h3>
                            <h4 style="font-size:3rem; font-weight:bold; margin-bottom:0;">
                                <span style="color:#fb0001">D</span>eliver<br />
                                <span style="color:#ed7c31">A</span>ctive<br />
                                <span style="color:#fdc000">V</span>isible<br />
                                <span style="color:#23b050">E</span>xcellence <span style="color:#203878">...on</span> YOUR <span style="color:#203878">Sites!</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
  </div>
</section>
<script>
    //Toggle show/hide help section
    $('#signup-button').click(function () {
        if ($('#signup-message').is(":visible")) {
            $('#signup-message').slideUp();
        } else {
            $('#signup-message').slideDown();
        }
    });
</script>



@endsection
