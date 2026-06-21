@props([
    'route',
    'active'
])

<a href="{{ route($route) }}"
   {{ $attributes->merge([
       'class' => request()->routeIs($active)
           ? 'flex items-center gap-3 px-4 py-3 bg-indigo-50/60 border border-indigo-100/50 rounded-xl text-indigo-600 font-bold text-sm'
           : 'flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 text-sm hover:bg-gray-50'
   ]) }}>
    {{ $slot }}
</a>