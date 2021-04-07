@props(['disabled' => false, 'textarea' => false])

@php
$classes = 'form-input rounded-none border-0 border-b focus:ring-0 focus:border-red-800 focus:border-b-2';

if($disabled){
    $classes .= " bg-gray-200";
}
@endphp

@if($textarea)
    <textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}></textarea>
@else
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
@endif
