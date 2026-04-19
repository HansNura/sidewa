{{-- Animated Background Decorations --}}
<div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none" aria-hidden="true">
    {{-- Green Blob - Top Right --}}
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-green-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
    </div>

    {{-- Emerald Blob - Bottom Left --}}
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
    </div>

    {{-- Teal Blob - Center Left --}}
    <div class="absolute top-40 left-20 w-72 h-72 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000">
    </div>

    {{-- Subtle Pattern Overlay --}}
    <div class="absolute inset-0 opacity-[0.03]"
         style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;40&quot; height=&quot;40&quot; viewBox=&quot;0 0 40 40&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%239C92AC&quot; fill-opacity=&quot;0.4&quot; fill-rule=&quot;evenodd&quot;%3E%3Cpath d=&quot;M0 20L20 0h20v20L20 40H0z&quot;/%3E%3C/g%3E%3C/svg%3E');">
    </div>
</div>
