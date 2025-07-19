<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Primary Meta Tags -->
    <title>{{ $restaurant->name }} - {{ $restaurant->tagline ?? 'Restaurant' }} | {{ config('app.name') }}</title>
    <meta name="title" content="{{ $restaurant->name }} - {{ $restaurant->tagline ?? 'Restaurant' }}">
    <meta name="description"
        content="{{ $restaurant->description ?? 'Experience exceptional dining at ' . $restaurant->name }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $restaurant->name }} - {{ $restaurant->tagline ?? 'Restaurant' }}">
    <meta property="og:description"
        content="{{ $restaurant->description ?? 'Experience exceptional dining at ' . $restaurant->name }}">
    @if($restaurant->config?->logo_url)
    <meta property="og:image" content="{{ $restaurant->config->logo_url }}">
    @endif

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $restaurant->name }} - {{ $restaurant->tagline ?? 'Restaurant' }}">
    <meta property="twitter:description"
        content="{{ $restaurant->description ?? 'Experience exceptional dining at ' . $restaurant->name }}">
    @if($restaurant->config?->logo_url)
    <meta property="twitter:image" content="{{ $restaurant->config->logo_url }}">
    @endif

    <!-- Restaurant Info -->
    <meta name="restaurant:name" content="{{ $restaurant->name }}">
    @if($restaurant->contact?->phone)
    <meta name="restaurant:phone" content="{{ $restaurant->contact->phone }}">
    @endif
    @if($restaurant->contact?->displayAddress)
    <meta name="restaurant:address" content="{{ $restaurant->contact->displayAddress }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Favicon -->
    @if($restaurant->config?->logo_url)
    <link rel="icon" href="{{ $restaurant->config->logo_url }}">
    @else
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Styles -->
    @vite(['resources/css/app.css'])

    <!-- Custom CSS Variables for Restaurant Branding -->
    <style>
        :root {
            --primary-color: {{$restaurant->config?->primary_color ?? '#ff8904'}};
            --secondary-color: {{$restaurant->config?->secondary_color ?? '#475569'}};
        }
    </style>
</head>

<body class="font-inter antialiased bg-white text-gray-900">
    <div id="restaurant-app">
        <!-- Loading State -->
        <div id="loading" class="min-h-screen bg-white flex items-center justify-center">
            <div class="text-center">
                @if($restaurant->config?->logo_url)
                <img src="{{ $restaurant->config->logo_url }}" alt="{{ $restaurant->name }}"
                    class="w-16 h-16 mx-auto mb-4 rounded-lg object-cover">
                @endif
                <div
                    class="w-8 h-8 mx-auto border-4 border-gray-200 border-t-[var(--primary-color)] rounded-full animate-spin">
                </div>
                <p class="mt-4 text-gray-600">Loading {{ $restaurant->name }}...</p>
            </div>
        </div>
    </div>

    <!-- Restaurant Data -->
    <script type="application/json" id="restaurant-data">
        {!! json_encode($restaurant) !!}
    </script>

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Restaurant",
        "name": "{{ $restaurant->name }}",
        "description": "{{ $restaurant->description }}",
        "url": "{{ url()->current() }}",
        @if($restaurant->config?->logo_url)
        "logo": "{{ $restaurant->config->logo_url }}",
        "image": "{{ $restaurant->config->logo_url }}",
        @endif
        "priceRange": "$$",
        "acceptsReservations": true,
        "address": {
            "@type": "PostalAddress",
            @if($restaurant->contact?->displayAddress)
            "streetAddress": "{{ $restaurant->contact->displayAddress }}",
            @endif
            @if($restaurant->contact?->city)
            "addressLocality": "{{ $restaurant->contact->city }}",
            @endif
            @if($restaurant->contact?->state)
            "addressRegion": "{{ $restaurant->contact->state }}",
            @endif
            @if($restaurant->contact?->zipCode)
            "postalCode": "{{ $restaurant->contact->zipCode }}",
            @endif
            "addressCountry": "{{ $restaurant->contact?->country?->name ?? 'US' }}"
        },
        @if($restaurant->contact?->phone)
        "telephone": "{{ $restaurant->contact->phone }}",
        @endif
        @if($restaurant->contact?->email)
        "email": "{{ $restaurant->contact->email }}",
        @endif
        "hasMenu": "{{ url()->current() }}#menu",
        "acceptsCreditCards": true
    }
    </script>

    <!-- Scripts -->
    @vite(['resources/js/restaurant.js'])
</body>

</html>