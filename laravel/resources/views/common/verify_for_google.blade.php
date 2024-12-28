<!-- Declare site name for google  -->
<meta property="og:site_name" content="GMOポイ活">
@yield('layout.structure_data_review')
<script type="application/ld+json">
        
        {
         "@context": "https://schema.org",
         "@type": "WebSite",
         "name": "GMOポイ活",
         "url": "{{ $_ENV['APP_URL'] }}"
        }
</script>


@if(!empty($application_json))
<script type="application/ld+json">
        {
         "@context": "https://schema.org",
         "@type": "BreadcrumbList",
         "itemListElement": [
            {!! $application_json !!}
        ]
        }
        </script>
        @endif
<!-- End declare site name for google  -->
