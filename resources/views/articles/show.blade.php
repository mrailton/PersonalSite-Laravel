<x-layout.app>
    <x-slot name="head">
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:site" content="@markrailton84"/>

        <meta property="og:type" content="article"/>
        <meta property="og:site_name" content="Mark Railton"/>
        <meta property="og:locale" content="en_IE"/>
        <meta property="og:title" content="{{ $article->title }}"/>
        <meta property="og:description" content="Article by Mark Railton"/>
        <meta property="og:url" content="{{ route('articles.show', ['article' => $article]) }}"/>
    </x-slot>

    <div class="max-w-4xl px-6 pb-20 mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-4">{{ $article->title }}</h1>
        <span
            class="block text-gray-600 font-light text-sm mb-8">Posted: {{ Carbon\Carbon::parse($article->published_at)->format('jS F Y') }}</span>
        <div class="prose lg:prose-xl">
            {!! $html !!}
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });
            });
        </script>
    @endpush
</x-layout.app>
