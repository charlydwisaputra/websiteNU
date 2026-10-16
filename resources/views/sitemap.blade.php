{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('tentang') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ route('ekosistem') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('berita.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('galeri') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('anggota') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    @foreach($beritas as $berita)
    <url>
        <loc>{{ route('berita.show', $berita->id) }}</loc>
        <lastmod>{{ $berita->updated_at->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>
