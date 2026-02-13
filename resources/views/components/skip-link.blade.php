{{--
Skip to Content Link - Accessibility
Include at the very top of layouts, right after <body>
Usage: @include('components.skip-link')
--}}

<a 
    href="#main-content" 
    class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-blue-600 focus:text-white focus:px-4 focus:py-2 focus:rounded-lg focus:shadow-lg focus:outline-none"
>
    Langsung ke konten utama
</a>
