@extends('landing.layouts.app')

@section('content')
    {{-- Navigation --}}
    @include('landing.components.layout.navbar')

    {{-- Hero Section --}}
    @include('landing.sections.hero')

    {{-- Trust Badges (ISO, PSE) --}}
    @include('landing.sections.trust-badges')

    {{-- About Section --}}
    @include('landing.sections.about')

    {{-- Services Section --}}
    @include('landing.sections.services')

    {{-- Features/Why Choose Us --}}
    @include('landing.sections.features')

    {{-- Stats/Counter --}}
    @include('landing.sections.stats')

    {{-- How It Works --}}
    @include('landing.sections.how-it-works')

    {{-- Industries We Serve --}}
    @include('landing.sections.industries')

    {{-- Security & Compliance --}}
    @include('landing.sections.security')

    {{-- CTA Section --}}
    @include('landing.sections.cta')

    {{-- Contact Form --}}
    @include('landing.sections.contact')

    {{-- Footer --}}
    @include('landing.components.layout.footer')

    {{-- Floating Buttons --}}
    @include('landing.components.layout.whatsapp-button')
    @include('landing.components.layout.scroll-to-top')
@endsection