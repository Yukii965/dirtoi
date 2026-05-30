<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-sm transition-colors duration-150']) }}
   style="color: rgba(253,246,236,0.8);"
   onmouseover="this.style.background='rgba(244,164,41,0.15)'; this.style.color='#F4A429'"
   onmouseout="this.style.background='transparent'; this.style.color='rgba(253,246,236,0.8)'">
    {{ $slot }}
</a>