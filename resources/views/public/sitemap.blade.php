@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/sobre') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/contato') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    @foreach ($services as $service)
        <url>
            <loc>{{ route('services.show', $service->slug) }}</loc>
            <lastmod>{{ $service->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach

    @foreach ($projects as $project)
        <url>
            <loc>{{ route('projects.show', $project->slug) }}</loc>
            <lastmod>{{ $project->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    @foreach ($posts as $post)
        <url>
            <loc>{{ route('blog.show', $post->slug) }}</loc>
            <lastmod>{{ ($post->published_at ?? $post->updated_at)->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>
