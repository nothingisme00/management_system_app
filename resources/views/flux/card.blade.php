@props([
    'variant' => 'default',
])

<div {{ $attributes->class([
    'rounded-lg border bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800',
    'border-zinc-200' => $variant === 'default',
]) }}>
    {{ $slot }}
</div>
