@props(['comment', 'chapter', 'isReply' => false])

<div id="comment-{{ $comment->id }}" @class(['flex gap-3 md:gap-4 group', 'ml-8 md:ml-12 border-l-2 border-slate-100 dark:border-slate-800 pl-4' => $isReply])>
    @if($comment->user)
        <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}"
           class="w-8 h-8 md:w-10 md:h-10 flex-shrink-0 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold text-xs md:text-sm hover:ring-2 hover:ring-slate-500/40 transition-all">
            @if($comment->user->profile_photo)
                <img src="{{ asset('storage/' . $comment->user->profile_photo) }}" alt="" class="w-full h-full object-cover rounded-full" onerror="this.onerror=null; this.src='/error.png'">
            @else
                {{ substr($comment->user->name, 0, 1) }}
            @endif
        </a>
    @else
        <div class="w-8 h-8 md:w-10 md:h-10 flex-shrink-0 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 font-bold text-xs">?</div>
    @endif
    <div class="flex-grow min-w-0">
        <div class="flex items-center justify-between mb-1 gap-2">
            @if($comment->user)
                <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}" class="font-bold text-xs md:text-sm text-slate-900 dark:text-white truncate">{{ $comment->user->name }}</a>
            @else
                <span class="font-bold text-xs md:text-sm text-slate-400 italic">Deleted User</span>
            @endif
            <span class="text-[9px] md:text-[10px] font-medium text-slate-400 uppercase tracking-widest shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $comment->content }}</p>

        <div class="mt-3 flex flex-wrap items-center gap-3">
            <div class="flex items-center bg-slate-50 dark:bg-slate-800 rounded-lg p-1 border border-slate-100 dark:border-slate-700">
                @auth
                    <form action="{{ route('reactions.toggle', ['type' => 'comment', 'id' => $comment->id]) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="reaction_type" value="like">
                        <button type="submit" class="flex items-center gap-1.5 px-2 py-1 rounded-md transition-all {{ $comment->likes->where('user_id', Auth::id())->first() ? 'text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-700' : 'text-slate-500 hover:text-slate-900' }}">
                            <span class="text-[10px] font-bold">{{ $comment->likes->count() }}</span>
                        </button>
                    </form>
                @else
                    <span class="text-[10px] font-bold text-slate-500 px-2">{{ $comment->likes->count() }}</span>
                @endauth
            </div>

            @can('delete', $comment)
                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-slate-400 hover:text-red-500 text-[9px] font-bold uppercase tracking-widest" onclick="return confirm('Hapus komentar ini?')">Hapus</button>
                </form>
            @endcan

            @auth
                @if(! $isReply && $comment->user)
                    <button type="button"
                            @click="$dispatch('open-reply', { parentId: {{ $comment->id }}, name: @js($comment->user->name) })"
                            class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-indigo-500 transition-colors">
                        Balas
                    </button>
                @endif
                @if($comment->user && $comment->user_id !== Auth::id())
                    @include('partials.report-trigger', [
                        'type' => 'comment',
                        'id' => $comment->id,
                        'label' => 'Komentar oleh '.$comment->user->name,
                    ])
                @endif
            @endauth
        </div>

        @if(! $isReply)
            @foreach($comment->replies as $reply)
                @include('partials.comment-item', ['comment' => $reply, 'chapter' => $chapter, 'isReply' => true])
            @endforeach
        @endif
    </div>
</div>
