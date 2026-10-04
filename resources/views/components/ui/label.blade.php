{{-- 
props
    type 
    * default
    * checkbox
 --}}

@props(['type' => 'default'])

@php
    $class = '';
    switch ($type) {
        case 'checkbox':
            $class = 'select-none ms-2 text-sm font-medium text-heading';
            break;

        default:
            $class = 'block mb-2.5 text-sm font-medium text-heading';
            break;
    }
@endphp

<label {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</label>
