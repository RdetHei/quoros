@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm ' . $class]) }}>
    {{ $slot }}
</div>

