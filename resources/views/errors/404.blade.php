@extends('layouts.error')

@section('content')
<section class="hero is-primary is-fullheight">
  <div class="hero-body">
    <div class="container">
        <div class="card egl-base">
            <header class="card-header">
                <p class="card-header-title">
                    404
                </p>
                <a class="card-header-icon">
                    <span class="site-logo" style="background-image:url('/logo.png');">
                    </span>
                </a>
            </header>
            <div class="card-content">
                <div class="content">
                    <p>Sorry, the page you are looking for could not be found.</p>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>



@endsection
