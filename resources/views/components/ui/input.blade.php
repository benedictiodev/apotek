@php
    $class = '';
    switch ($attributes['type']) {
        case 'text':
            $class =
                'bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body';
            break;

        case 'checkbox':
            $class =
                'w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft';
            break;

        case 'radio':
            $class =
                'w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none';
            break;

        default:
            $class =
                'bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body';
            break;
    }
@endphp

<input {{ $attributes->merge(['class' => $class]) }} />
