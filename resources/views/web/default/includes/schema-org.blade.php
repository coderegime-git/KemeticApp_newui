{{-- Schema.org JSON-LD Markup --}}
@php
    $currentUrl = url()->current();
    $isHomepage = $currentUrl === url('/');
    $logoUrl = !empty($generalSettings['logo']) ? $generalSettings['logo'] : 'https://kemetic.app/logo.png';
@endphp

@if($isHomepage)
    {{-- MAIN SCHEMA FOR HOMEPAGE: MobileApplication + Organization in @graph --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "@id": "{{ url('/') }}#org",
                "name": "BLACKBEACON B.V.",
                "url": "{{ url('/') }}",
                "brand": {
                    "@type": "Brand",
                    "name": "Kemetic App"
                },
                "logo": {
                    "@type": "ImageObject",
                    "url": "{{ $logoUrl }}"
                }
            },
            {
                "@type": "MobileApplication",
                "@id": "{{ url('/') }}#app",
                "name": "Kemetic App",
                "alternateName": "Kemetic",
                "url": "{{ url('/') }}",
                "applicationCategory": "EducationApplication",
                "operatingSystem": "iOS, Android",
                "isAccessibleForFree": true,
                "publisher": {
                    "@id": "{{ url('/') }}#org"
                },
                "description": "Kemetic App is a global knowledge platform for seekers, wisdom keepers, and conscious creators. Explore portals (short videos), courses, scrolls, and articles focused on spiritual growth, hidden knowledge, and self-mastery. Content rises through global rankings using a chakra-based star rating system.",
                "inLanguage": ["en"],
                "installUrl": "https://apps.apple.com/us/app/kemetic-app/id6479200304",
                "downloadUrl": "https://apps.apple.com/us/app/kemetic-app/id6479200304",
                "sameAs": [
                    "https://apps.apple.com/us/app/kemetic-app/id6479200304"
                ],
                "offers": [
                    {
                        "@type": "Offer",
                        "category": "subscription",
                        "priceCurrency": "EUR",
                        "price": "1.00",
                        "name": "Monthly Membership",
                        "url": "{{ url('/') }}"
                    },
                    {
                        "@type": "Offer",
                        "category": "subscription",
                        "priceCurrency": "EUR",
                        "price": "10.00",
                        "name": "Yearly Membership",
                        "url": "{{ url('/') }}"
                    },
                    {
                        "@type": "Offer",
                        "category": "lifetime",
                        "priceCurrency": "EUR",
                        "price": "33.00",
                        "name": "Lifetime Access",
                        "url": "{{ url('/') }}"
                    }
                ],
                "image": [
                    "{{ $logoUrl }}"
                ]
            }
        ]
    }
    </script>

    {{-- WEBSITE SCHEMA FOR HOMEPAGE --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "@id": "{{ url('/') }}#website",
        "name": "Kemetic App",
        "url": "{{ url('/') }}",
        "publisher": {
            "@id": "{{ url('/') }}#org"
        },
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/') }}/search?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
@else
    {{-- FOR NON-HOMEPAGE PAGES: WebSite + Organization --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Kemetic App",
        "url": "{{ url('/') }}",
        "publisher": {
            "@type": "Organization",
            "name": "BLACKBEACON B.V.",
            "url": "{{ url('/') }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ $logoUrl }}"
            }
        },
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/') }}/search?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "BLACKBEACON B.V.",
        "url": "{{ url('/') }}",
        "brand": {
            "@type": "Brand",
            "name": "Kemetic App"
        },
        "logo": {
            "@type": "ImageObject",
            "url": "{{ $logoUrl }}"
        }
    }
    </script>
@endif

{{-- DYNAMIC SCHEMA FOR SPECIFIC CONTENT TYPES --}}
@if(isset($pageSchemaType))
    @switch($pageSchemaType)
        @case('article')
            @if(isset($pageSchemaData))
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Article",
                "headline": "{{ $pageSchemaData['title'] ?? $pageTitle ?? '' }}",
                "description": "{{ $pageSchemaData['description'] ?? $pageDescription ?? '' }}",
                "image": ["{{ $pageSchemaData['image'] ?? $pageMetaImage ?? $logoUrl }}"],
                "datePublished": "{{ $pageSchemaData['published'] ?? now()->toIso8601String() }}",
                "dateModified": "{{ $pageSchemaData['modified'] ?? now()->toIso8601String() }}",
                "author": {
                    "@type": "Person",
                    "name": "{{ $pageSchemaData['author'] ?? 'Kemetic Wisdom Keeper' }}"
                },
                "publisher": {
                    "@type": "Organization",
                    "name": "Kemetic App",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "{{ $logoUrl }}"
                    }
                },
                "mainEntityOfPage": {
                    "@type": "WebPage",
                    "@id": "{{ $currentUrl }}"
                }
            }
            </script>
            @endif
            @break
        
        @case('video')
            @if(isset($pageSchemaData))
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "VideoObject",
                "name": "{{ $pageSchemaData['title'] ?? $pageTitle ?? '' }}",
                "description": "{{ $pageSchemaData['description'] ?? $pageDescription ?? '' }}",
                "thumbnailUrl": ["{{ $pageSchemaData['thumbnail'] ?? $pageMetaImage ?? $logoUrl }}"],
                "uploadDate": "{{ $pageSchemaData['uploaded'] ?? now()->toIso8601String() }}",
                "duration": "{{ $pageSchemaData['duration'] ?? 'PT45S' }}",
                @if(isset($pageSchemaData['contentUrl']))
                "contentUrl": "{{ $pageSchemaData['contentUrl'] }}",
                @endif
                @if(isset($pageSchemaData['embedUrl']))
                "embedUrl": "{{ $pageSchemaData['embedUrl'] }}",
                @endif
                "publisher": {
                    "@type": "Organization",
                    "name": "Kemetic App",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "{{ $logoUrl }}"
                    }
                },
                "mainEntityOfPage": {
                    "@type": "WebPage",
                    "@id": "{{ $currentUrl }}"
                }
            }
            </script>
            @endif
            @break
    @endswitch
@endif