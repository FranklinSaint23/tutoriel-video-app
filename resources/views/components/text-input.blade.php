@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-gray-950 border-gray-800 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-sm text-gray-300 placeholder-gray-600']) !!}>