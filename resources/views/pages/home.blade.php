@extends('layouts.portfolio')

@section('content')
    <div class="pt-16 md:pt-20">
        @include('portfolio.components.hero', $hero)
        @include('portfolio.components.problem')
        @include('portfolio.components.about', $about)
        @include('portfolio.components.case-studies')
        @include('portfolio.components.offers')
        @include('portfolio.components.skills', ['skillsGrouped' => $skillsGrouped])
        @include('portfolio.components.services', ['services' => $services])
        @include('portfolio.components.portfolio', ['projects' => $projects])
        @include('portfolio.components.blog', ['blogPosts' => $blogPosts])
        @include('portfolio.components.contact', $contact)
        @include('portfolio.components.calendly', ['calendlyUrl' => $contact['calendlyUrl'] ?? null])
    </div>
@endsection
