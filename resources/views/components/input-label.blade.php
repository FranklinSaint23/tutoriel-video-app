@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-black text-xs uppercase tracking-widest text-gray-400 mb-1']) }}>
    {{ $value ?? $slot }}
</label>