@php
    $personSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $heroName ?? config('app.name'),
        'jobTitle' => \App\Models\SiteSetting::get('hero_title'),
        'description' => $metaDescription ?? config('app.name'),
        'url' => url('/'),
    ];
    if ($ogImage ?? null) {
        $personSchema['image'] = $ogImage;
    }
    $email = \App\Models\SiteSetting::get('contact_email');
    if ($email) {
        $personSchema['email'] = $email;
    }
    $webSiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $metaTitle ?? config('app.name'),
        'description' => $metaDescription ?? config('app.name'),
        'url' => url('/'),
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($personSchema) !!}
</script>
<script type="application/ld+json">
{!! json_encode($webSiteSchema) !!}
</script>
