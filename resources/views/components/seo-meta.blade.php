@props([
    'title' => config('organisasi.nama'),
    'description' => config('organisasi.deskripsi'),
    'image' => asset('images/flag-nu.jpg'),
    'url' => url()->current(),
    'type' => 'website',
])

<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $url }}">
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

{{-- Open Graph --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:site_name" content="{{ config('organisasi.nama') }}">
<meta property="og:locale" content="id_ID">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
