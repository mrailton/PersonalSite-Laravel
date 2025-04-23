<a class="mb-8 max-w-3xl w-full block bg-white shadow-md border-t-4 border-indigo-600" href="{{ route('articles.show', ['article' => $article]) }}">
    <div class="flex items-center justify-between px-4 py-2">
        <h3 class="text-lg font-medium text-gray-700 max-w-[80%] text-left">
            {{ $article->title }}
        </h3>

        <span class="block text-gray-600 font-light text-sm text-right">
            {{ \Carbon\Carbon::parse($article->published_at)->format('jS M Y') }}
        </span>
    </div>
</a>
