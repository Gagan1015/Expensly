@extends('landing.layouts.app')

@section('content')
    @include('landing.components.hero')
    @include('landing.components.features')
    @include('landing.components.stats')
    @include('landing.components.testimonials')
    @include('landing.components.cta')
@endsection
