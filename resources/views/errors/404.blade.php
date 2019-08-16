@extends('layouts.error')

@section('content')
@php
    $authUser = Auth::user();
@endphp
<section class="hero is-primary is-fullheight">
  <div class="hero-body">
    <div class="container">
        <div class="card egl-base">
            <header class="card-header">
                <p class="card-header-title">
                    404
                </p>
                <a class="card-header-icon">
                    @if (isset($authUser->company) && $authUser->company->logo !== null) 
                        <span class="site-logo" style="background-image:url('/image/{{ $authUser->company->logo }}');"
                    @else
                        <span class="site-logo" style="background-image:url('/logo.png');"
                    @endif
                    @if (config('egc.sidebar_logo_href.on'))
                        @if (is_null(config('egc.sidebar_logo_href.on')))
                         onclick="document.location = '/';"
                        @else
                         onclick="document.location = '{{config('egc.sidebar_logo_href.url')}}';"
                        @endif
                    @endif
                    ></span>
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
@if (isset($authUser->company) && $authUser->company != null) 
    @push ('styles')
        <style> 
            .hero.is-primary {
                background-color: {{ $authUser->company->primary_colour }};
            } 
        </style>
    @endpush
@endif
