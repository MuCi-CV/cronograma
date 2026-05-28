<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tracker Áreas Verdes') · MuCi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-black font-sans min-h-screen">

<header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-muci-gray-light">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between gap-4 flex-wrap">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-muci-green-mid">MuCi · Áreas Verdes</p>
            <h1 class="text-base font-bold text-black leading-tight">Tracker de Paisajismo</h1>
        </div>

        {{-- Barra de progreso general --}}
        @isset($stages)
        @php
            $allItems = $stages->flatMap(fn($s) => $s->sections->flatMap->items);
            $totalItems = $allItems->count();
            $doneItems = $allItems->filter(fn($i) => $i->isCompleted)->count();
            $globalPct = $totalItems > 0 ? round($doneItems / $totalItems * 100) : 0;
        @endphp
        <div class="flex items-center gap-3">
            <div class="w-36 hidden sm:block">
                <div class="flex justify-between text-xs text-muci-gray mb-1">
                    <span class="font-semibold uppercase tracking-wider text-muci-green" style="font-size:.6rem">Progreso general</span>
                    <span class="font-bold text-black">{{ $globalPct }}%</span>
                </div>
                <div class="h-1.5 bg-muci-green-pale rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-muci-green transition-all" style="width: {{ $globalPct }}%"></div>
                </div>
            </div>
            <span class="text-2xl font-bold text-black">{{ $globalPct }}%</span>
        </div>
        @endisset

        {{-- Nav y auth --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('tracker.visual') }}"
               class="text-xs font-medium px-3 py-1.5 rounded-full border transition
                      {{ request()->routeIs('tracker.visual') ? 'bg-black text-white border-black' : 'border-muci-gray-light text-muci-dark hover:border-muci-green' }}">
                Visual
            </a>
            <a href="{{ route('tracker.detail') }}"
               class="text-xs font-medium px-3 py-1.5 rounded-full border transition
                      {{ request()->routeIs('tracker.detail') ? 'bg-black text-white border-black' : 'border-muci-gray-light text-muci-dark hover:border-muci-green' }}">
                Detalle
            </a>
            @auth
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-xs font-medium px-3 py-1.5 rounded-full border border-muci-gray-light text-muci-gray hover:border-red-400 hover:text-red-500 transition">
                        Salir
                    </button>
                </form>
            @else
                <a href="{{ route('auth.google') }}"
                   class="text-xs font-medium px-3 py-1.5 rounded-full border border-muci-gray-light text-muci-gray hover:border-muci-green hover:text-muci-green transition">
                    Editar
                </a>
            @endauth
        </div>
    </div>
</header>

@if(session('error'))
<div class="max-w-5xl mx-auto px-4 pt-3">
    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-2">
        {{ session('error') }}
    </div>
</div>
@endif

<main>
    @yield('content')
</main>

{{-- Galería modal --}}
<div id="gallery-overlay"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.85);align-items:center;justify-content:center;"
     onclick="if(event.target===this)closeGallery()">
    <button onclick="closeGallery()"
            style="position:absolute;top:1rem;right:1.25rem;color:#fff;font-size:1.75rem;line-height:1;background:none;border:none;cursor:pointer;opacity:.8;">×</button>
    <button id="gallery-prev"
            style="position:absolute;left:1rem;color:#fff;font-size:2rem;background:none;border:none;cursor:pointer;padding:.5rem;opacity:.7;"
            onclick="galleryNav(-1)">‹</button>
    <img id="gallery-img"
         style="max-width:90vw;max-height:90vh;object-fit:contain;border-radius:8px;display:block;">
    <button id="gallery-next"
            style="position:absolute;right:1rem;color:#fff;font-size:2rem;background:none;border:none;cursor:pointer;padding:.5rem;opacity:.7;"
            onclick="galleryNav(1)">›</button>
    <div id="gallery-counter"
         style="position:absolute;bottom:1rem;left:50%;transform:translateX(-50%);color:#fff;font-size:.75rem;opacity:.7;"></div>
</div>

<script>
(function() {
    let _urls = [], _idx = 0;

    window.openGallery = function(urls, index) {
        _urls = urls;
        _idx = index;
        show();
        document.getElementById('gallery-overlay').style.display = 'flex';
        document.addEventListener('keydown', onKey);
    };

    window.closeGallery = function() {
        document.getElementById('gallery-overlay').style.display = 'none';
        document.removeEventListener('keydown', onKey);
    };

    window.galleryNav = function(dir) {
        _idx = (_idx + dir + _urls.length) % _urls.length;
        show();
    };

    function show() {
        document.getElementById('gallery-img').src = _urls[_idx];
        document.getElementById('gallery-counter').textContent = (_idx + 1) + ' / ' + _urls.length;
        document.getElementById('gallery-prev').style.display = _urls.length > 1 ? 'block' : 'none';
        document.getElementById('gallery-next').style.display = _urls.length > 1 ? 'block' : 'none';
    }

    function onKey(e) {
        if (e.key === 'Escape') closeGallery();
        if (e.key === 'ArrowLeft')  galleryNav(-1);
        if (e.key === 'ArrowRight') galleryNav(1);
    }
})();
</script>

</body>
</html>
