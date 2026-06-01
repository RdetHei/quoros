{{-- Usage: @include('partials.report-trigger', ['type' => 'novel', 'id' => $novel->id, 'label' => $novel->title]) --}}
@auth
<button type="button"
        @click="$dispatch('open-report', { type: '{{ $type }}', id: {{ $id }}, label: @js($label ?? '') })"
        class="{{ $class ?? 'text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-rose-500 transition-colors' }}">
    {{ $text ?? 'Laporkan' }}
</button>
@endauth
