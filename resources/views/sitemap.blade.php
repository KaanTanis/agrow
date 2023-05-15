<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($collect as $type)
        @foreach($type as $item)
            <url>
                <loc>{{ $item['loc'] }}</loc>
                <lastmod>{{ $item['lastmod'] }}</lastmod>
                <changefreq>{{ $item['changefreq'] }}</changefreq>
                <priority>{{ $item['priority'] }}</priority>
            </url>
        @endforeach
    @endforeach
</urlset>
