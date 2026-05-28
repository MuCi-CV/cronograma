@extends('layouts.app')
@section('title', 'Vista Visual')

@section('content')
@php
    $stage2026 = $stages->firstWhere('year', 2026);
    $sections2026 = $stage2026 ? $stage2026->sections : collect();
    $currentIndex = $sections2026->search(fn($s) => str_contains($s->name, 'Mayo'));
    if ($currentIndex === false) $currentIndex = 0;
@endphp

{{-- Toggle de vista --}}
<div class="max-w-5xl mx-auto px-4 pt-4 flex justify-end">
    <div class="flex gap-1 bg-gray-100 p-0.5 rounded-full">
        <button data-view="carousel"
                class="view-toggle-btn text-xs font-semibold px-4 py-1.5 rounded-full transition">
            Carrusel
        </button>
        <button data-view="timeline"
                class="view-toggle-btn text-xs font-semibold px-4 py-1.5 rounded-full transition">
            Timeline
        </button>
    </div>
</div>

{{-- VISTA CARRUSEL --}}
<div id="view-carousel" class="view-container">

    {{-- Stage pills --}}
    <div class="max-w-5xl mx-auto px-4 pt-4 flex gap-2 justify-center flex-wrap">
        @foreach($stages as $stage)
        <div class="text-xs font-medium px-3 py-1 rounded-full border border-muci-gray-light text-muci-dark">
            Etapa {{ $loop->iteration }} · {{ $stage->year }}
            <span class="text-muci-green font-semibold ml-1">{{ $stage->progressPct() }}%</span>
        </div>
        @endforeach
    </div>

    {{-- Carrusel 3D --}}
    <div class="carousel-wrap relative overflow-hidden" style="height:320px;">
        <div class="carousel-scene" style="perspective:1600px;perspective-origin:50% 45%;height:100%;">
            <div class="carousel-stage" id="carousel-stage" style="transform-style:preserve-3d;position:relative;height:100%;">
                @foreach($sections2026 as $index => $section)
                @php
                    $total = $section->items->count();
                    $done  = $section->items->filter(fn($i) => $i->isCompleted)->count();
                    $pct   = $total > 0 ? round($done / $total * 100) : 0;
                    $r     = 33;
                    $circ  = 2 * M_PI * $r;
                    $offset = $circ * (1 - $pct / 100);
                    $isCurrent = str_contains($section->name, 'Mayo');
                    $isPast    = in_array($index, [0, 1]);
                @endphp
                <div class="month-card {{ $isCurrent ? 'is-current' : ($isPast ? 'is-past' : 'is-future') }}"
                     data-index="{{ $index }}"
                     style="position:absolute;width:195px;left:50%;top:50%;background:#fff;border-radius:20px;padding:1.4rem 1.2rem 1.2rem;cursor:pointer;border:2px solid {{ $isCurrent ? '#00B26B' : ($isPast ? '#94CA9E' : '#E5E7EB') }};
                     {{ $isCurrent ? 'box-shadow:0 20px 50px rgba(0,178,107,.16);' : '' }}
                     will-change:transform,opacity;transition:transform .55s cubic-bezier(.4,0,.2,1),opacity .55s ease;">
                    @if($isCurrent)
                    <div style="position:absolute;top:-11px;left:50%;transform:translateX(-50%);background:#F37043;color:#fff;font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:.18rem .7rem;border-radius:99px;white-space:nowrap;">
                        Mes actual
                    </div>
                    @endif
                    <div style="font-size:.65rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#878787;margin-bottom:.15rem;">{{ $section->stage->year }}</div>
                    <div style="font-size:1.1rem;font-weight:700;color:{{ $isCurrent ? '#00B26B' : '#000' }};margin-bottom:.9rem;">
                        {{ explode(' ', $section->name)[0] }}
                    </div>
                    <div style="display:flex;justify-content:center;margin-bottom:.75rem;">
                        <div style="position:relative;width:76px;height:76px;">
                            <svg viewBox="0 0 76 76" style="width:76px;height:76px;transform:rotate(-90deg);">
                                <circle cx="38" cy="38" r="{{ $r }}" fill="none" stroke="#BCDDC0" stroke-width="6"/>
                                <circle cx="38" cy="38" r="{{ $r }}" fill="none"
                                        stroke="{{ $isCurrent ? '#00B26B' : ($isPast ? '#61B87C' : '#BCDDC0') }}"
                                        stroke-width="6" stroke-linecap="round"
                                        stroke-dasharray="{{ number_format($circ, 2) }}"
                                        stroke-dashoffset="{{ number_format($offset, 2) }}"/>
                            </svg>
                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:.95rem;font-weight:700;color:#000;">{{ $pct }}%</span>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:center;gap:.35rem;font-size:.7rem;color:#878787;">
                        <div style="width:7px;height:7px;border-radius:50%;background:{{ $done > 0 ? '#00B26B' : '#E5E7EB' }};flex-shrink:0;"></div>
                        <span>{{ $done }} / {{ $total }} ítems</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Navegación --}}
    <div style="display:flex;align-items:center;justify-content:center;gap:1.25rem;padding:1rem 0 .5rem;">
        <button id="carousel-prev" style="width:40px;height:40px;border-radius:50%;border:2px solid #B2B2B2;background:#fff;cursor:pointer;font-size:1.1rem;color:#00B26B;display:flex;align-items:center;justify-content:center;">&#8592;</button>
        <div id="carousel-dots" style="display:flex;gap:5px;"></div>
        <button id="carousel-next" style="width:40px;height:40px;border-radius:50%;border:2px solid #B2B2B2;background:#fff;cursor:pointer;font-size:1.1rem;color:#00B26B;display:flex;align-items:center;justify-content:center;">&#8594;</button>
    </div>

    {{-- Panel de detalle --}}
    <div id="carousel-detail" class="max-w-2xl mx-auto px-4 pb-8"></div>

</div>

{{-- VISTA TIMELINE --}}
<div id="view-timeline" class="view-container hidden">
    <div class="max-w-2xl mx-auto px-4 py-4">

        {{-- Año header --}}
        <div class="text-center mb-6">
            <span class="inline-flex items-center gap-2 bg-black text-white text-sm font-bold px-5 py-2 rounded-full">
                🌿 2026 — Etapa 1
            </span>
        </div>

        {{-- Timeline --}}
        <div class="relative">
            {{-- Línea central --}}
            <div class="absolute left-1/2 -translate-x-px top-0 bottom-0 w-0.5 bg-gradient-to-b from-muci-green-pale via-muci-green to-muci-green-pale hidden md:block"></div>
            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-muci-green-pale via-muci-green to-muci-green-pale md:hidden"></div>

            @foreach($sections2026 as $index => $section)
            @php
                $total = $section->items->count();
                $done  = $section->items->filter(fn($i) => $i->isCompleted)->count();
                $pct   = $total > 0 ? round($done / $total * 100) : 0;
                $isCurrent = str_contains($section->name, 'Mayo');
                $isPast    = in_array($index, [0, 1]);
                $state     = $isCurrent ? 'current' : ($isPast ? 'past' : 'future');
            @endphp

            <div class="flex {{ $index % 2 === 0 ? 'flex-row' : 'flex-row-reverse' }} mb-10 relative flex-col md:flex-row pl-14 md:pl-0 md:justify-between md:items-stretch"
                 id="{{ $isCurrent ? 'tl-current' : '' }}">

                {{-- Nodo desktop --}}
                <div class="absolute left-1/2 md:-translate-x-1/2 top-4 z-10 hidden md:flex items-center justify-center w-5 h-5">
                    @if($isCurrent)
                    <div class="w-4 h-4 rounded-full bg-muci-orange"
                         style="box-shadow:0 0 0 4px #FAC8AE,0 0 0 6px rgba(243,112,67,.3);animation:pulse 2.2s infinite;"></div>
                    @elseif($isPast)
                    <div class="w-3 h-3 rounded-full bg-muci-green-mid border-2 border-white"></div>
                    @else
                    <div class="w-3 h-3 rounded-full bg-muci-gray-light border-2 border-white"></div>
                    @endif
                </div>
                {{-- Nodo móvil --}}
                <div class="absolute left-6 top-4 z-10 flex items-center justify-center w-5 h-5 md:hidden">
                    @if($isCurrent)
                    <div class="w-4 h-4 rounded-full bg-muci-orange"
                         style="animation:pulse 2.2s infinite;"></div>
                    @elseif($isPast)
                    <div class="w-3 h-3 rounded-full bg-muci-green-mid border-2 border-white"></div>
                    @else
                    <div class="w-3 h-3 rounded-full bg-muci-gray-light border-2 border-white"></div>
                    @endif
                </div>

                {{-- Card --}}
                <div class="w-full md:w-[calc(50%-2.5rem)] {{ $index % 2 === 0 ? 'md:mr-auto' : 'md:ml-auto' }}
                            bg-white rounded-2xl border px-5 py-4 transition
                            {{ $isCurrent ? 'border-muci-green shadow-lg' : ($isPast ? 'border-muci-green-light opacity-85' : 'border-muci-gray-light opacity-70') }}">

                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-[.6rem] font-semibold uppercase tracking-widest text-muci-gray">{{ $section->stage->year }}</p>
                            <h3 class="font-bold text-black {{ $isCurrent ? 'text-muci-green' : '' }}">
                                {{ explode(' ', $section->name)[0] }}
                            </h3>
                        </div>
                        @if($isCurrent)
                        <span class="text-[.6rem] font-bold bg-muci-orange/10 text-muci-orange px-2 py-0.5 rounded-full uppercase tracking-wide">
                            Mes actual
                        </span>
                        @endif
                    </div>

                    {{-- Barra de progreso --}}
                    <div class="mb-3">
                        <div class="flex justify-between text-[.65rem] mb-1">
                            <span class="text-muci-gray">{{ $done }} / {{ $total }} ítems</span>
                            <span class="font-semibold {{ $pct > 0 ? 'text-muci-green' : 'text-muci-gray-light' }}">{{ $pct }}%</span>
                        </div>
                        <div class="h-1.5 bg-muci-green-pale rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $pct > 0 ? 'bg-muci-green' : 'bg-muci-gray-light' }}" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>

                    {{-- Toggle de ítems --}}
                    <details {{ $isCurrent ? 'open' : '' }}>
                        <summary class="text-xs font-semibold text-muci-green cursor-pointer list-none flex items-center gap-1">
                            <span>Ver objetivos y metas</span>
                        </summary>
                        <div class="mt-3 pt-3 border-t border-muci-green-pale/50 space-y-1.5">
                            @foreach($section->items as $item)
                            <div class="flex items-start gap-2 text-xs">
                                <div class="mt-0.5 w-3.5 h-3.5 rounded border flex-shrink-0 flex items-center justify-center
                                    {{ $item->isCompleted ? 'bg-muci-green border-muci-green' : 'border-muci-gray-light' }}">
                                    @if($item->isCompleted)
                                    <svg class="w-2 h-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </div>
                                <span class="{{ $item->isCompleted ? 'line-through text-muci-gray' : 'text-black' }}">
                                    {{ $item->text }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </details>

                    {{-- Imágenes (Layout C + fallback móvil de B) --}}
                    @php $sectionImages = $section->items->flatMap(fn($i) => $i->images->all()); @endphp
                    @if($sectionImages->isNotEmpty())
                    <div class="{{ $imageLayout === 'C' ? ($hideImagesMobile ? 'hidden md:flex' : 'flex') : ($hideImagesMobile ? 'hidden' : 'flex md:hidden') }} flex-wrap gap-1.5 mt-3 pt-3 border-t border-muci-green-pale/50">
                        @foreach($sectionImages->take(4) as $imgIndex => $img)
                        <img src="{{ \Storage::disk('public')->url($img->path) }}"
                             alt=""
                             class="w-12 h-12 object-cover rounded-lg cursor-pointer border border-muci-gray-light/50 hover:opacity-90 transition"
                             data-gallery-images="{{ e(json_encode($sectionImages->map(fn($i) => \Storage::disk('public')->url($i->path))->values()->all())) }}"
                             data-gallery-index="{{ $imgIndex }}"
                             onclick="openGallery(JSON.parse(this.dataset.galleryImages), parseInt(this.dataset.galleryIndex))">
                        @endforeach
                        @if($sectionImages->count() > 4)
                        <div class="w-12 h-12 rounded-lg bg-muci-green-pale flex items-center justify-center text-xs font-bold text-muci-green cursor-pointer"
                             data-gallery-images="{{ e(json_encode($sectionImages->map(fn($i) => \Storage::disk('public')->url($i->path))->values()->all())) }}"
                             data-gallery-index="4"
                             onclick="openGallery(JSON.parse(this.dataset.galleryImages), parseInt(this.dataset.galleryIndex))">
                            +{{ $sectionImages->count() - 4 }}
                        </div>
                        @endif
                    </div>
                    @endif

                </div>

                {{-- Layout B: imagen opuesta (solo desktop) --}}
                @php
                    $bImages = $section->items->flatMap(fn($i) => $i->images->all());
                    $firstImg = $bImages->first();
                @endphp
                @if($imageLayout === 'B' && $firstImg)
                <div class="hidden md:flex w-[calc(50%-2.5rem)] max-w-[280px] items-stretch">
                    <img src="{{ \Storage::disk('public')->url($firstImg->path) }}"
                         alt=""
                         class="w-full h-full object-cover rounded-2xl cursor-pointer hover:opacity-95 transition"
                         style="max-height: 320px;"
                         data-gallery-images="{{ e(json_encode($bImages->map(fn($i) => \Storage::disk('public')->url($i->path))->values()->all())) }}"
                         data-gallery-index="0"
                         onclick="openGallery(JSON.parse(this.dataset.galleryImages), 0)">
                </div>
                @endif
            </div>
            @endforeach
        </div>

    </div>
</div>

<style>
@keyframes pulse {
    0%,100% { box-shadow: 0 0 0 3px #FAC8AE, 0 0 0 5px rgba(243,112,67,.3); }
    50%      { box-shadow: 0 0 0 6px #FAC8AE, 0 0 0 10px rgba(243,112,67,.1); }
}
</style>

<script>
(function() {
// ── View toggle ──────────────────────────────────────────────
const STORAGE_KEY = 'muci-tracker-view';
const views = { carousel: document.getElementById('view-carousel'), timeline: document.getElementById('view-timeline') };
const toggleBtns = document.querySelectorAll('.view-toggle-btn');

function setView(v) {
    localStorage.setItem(STORAGE_KEY, v);
    Object.entries(views).forEach(([name, el]) => el.classList.toggle('hidden', name !== v));
    toggleBtns.forEach(btn => {
        const active = btn.dataset.view === v;
        btn.style.cssText = active
            ? 'background:#000;color:#fff;'
            : 'background:transparent;color:#575756;';
    });
}

toggleBtns.forEach(btn => btn.addEventListener('click', () => setView(btn.dataset.view)));
setView(localStorage.getItem(STORAGE_KEY) || 'carousel');

// ── Carrusel 3D ──────────────────────────────────────────────
const cards = Array.from(document.querySelectorAll('.month-card'));
const detailEl = document.getElementById('carousel-detail');
const dotsEl = document.getElementById('carousel-dots');
let cur = {{ $currentIndex }};

const sectionsData = {{ Js::from($sections2026->map(fn($s) => [
    'name'  => $s->name,
    'year'  => $s->stage->year,
    'items' => $s->items->map(fn($i) => [
        'text'        => $i->text,
        'type'        => $i->type,
        'isCompleted' => $i->isCompleted,
        'comment'     => $i->latestComment,
    ]),
])) }};

// Dots
cards.forEach((_, i) => {
    const d = document.createElement('div');
    d.style.cssText = 'width:6px;height:6px;border-radius:50%;background:#B2B2B2;cursor:pointer;transition:all .2s;';
    d.addEventListener('click', () => { cur = i; render(); });
    dotsEl.appendChild(d);
});

function render() {
    cards.forEach((card, i) => {
        const d = i - cur, a = Math.abs(d);
        const x = d * 215, z = -(a * a) * 38, ry = d * -17;
        const scale = Math.max(.42, 1 - a * .155);
        const op = Math.max(.3, 1 - a * .2);
        card.style.transform = `translate(-50%,-50%) translateX(${x}px) translateZ(${z}px) rotateY(${ry}deg) scale(${scale})`;
        card.style.opacity = op;
        card.style.zIndex = 20 - a;
        card.style.pointerEvents = a > 4 ? 'none' : 'auto';
    });
    dotsEl.querySelectorAll('div').forEach((d, i) => {
        d.style.background = i === cur ? '#00B26B' : '#B2B2B2';
        d.style.transform = i === cur ? 'scale(1.3)' : 'scale(1)';
    });
    renderDetail();
}

function renderDetail() {
    const s = sectionsData[cur];
    if (!s) return;
    while (detailEl.firstChild) detailEl.removeChild(detailEl.firstChild);

    const hd = document.createElement('div');
    hd.style.cssText = 'display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;';
    const tn = document.createElement('div');
    tn.style.cssText = 'font-size:1rem;font-weight:700;color:#000;';
    tn.textContent = '🌿 ' + s.name;
    hd.appendChild(tn);
    detailEl.appendChild(hd);

    const grid = document.createElement('div');
    grid.style.cssText = 'display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;';
    ['objetivo','meta'].forEach(type => {
        const col = document.createElement('div');
        const ch = document.createElement('div');
        ch.style.cssText = 'font-size:.62rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#00B26B;margin-bottom:.65rem;';
        ch.textContent = type === 'objetivo' ? 'Objetivos' : 'Metas';
        col.appendChild(ch);
        s.items.filter(i => i.type === type).forEach(item => {
            const row = document.createElement('div');
            row.style.cssText = 'display:flex;gap:.4rem;margin-bottom:.5rem;font-size:.78rem;';
            const chk = document.createElement('div');
            chk.style.cssText = `width:15px;height:15px;border-radius:3px;border:1.5px solid ${item.isCompleted?'#00B26B':'#D1D5DB'};flex-shrink:0;margin-top:1px;display:flex;align-items:center;justify-content:center;font-size:.55rem;color:#fff;${item.isCompleted?'background:#00B26B;':''}`;
            if (item.isCompleted) chk.textContent = '✓';
            const sp = document.createElement('span');
            sp.style.cssText = item.isCompleted ? 'color:#878787;text-decoration:line-through;' : 'color:#000;';
            sp.textContent = item.text;
            row.appendChild(chk); row.appendChild(sp); col.appendChild(row);
        });
        grid.appendChild(col);
    });
    detailEl.appendChild(grid);
}

document.getElementById('carousel-prev').addEventListener('click', () => { if (cur > 0) { cur--; render(); } });
document.getElementById('carousel-next').addEventListener('click', () => { if (cur < cards.length - 1) { cur++; render(); } });
document.addEventListener('keydown', e => {
    if (e.key === 'ArrowLeft' && cur > 0) { cur--; render(); }
    if (e.key === 'ArrowRight' && cur < cards.length - 1) { cur++; render(); }
});
let tx = 0;
document.querySelector('.carousel-wrap').addEventListener('touchstart', e => { tx = e.touches[0].clientX; });
document.querySelector('.carousel-wrap').addEventListener('touchend', e => {
    const dx = e.changedTouches[0].clientX - tx;
    if (Math.abs(dx) > 40) { if (dx < 0 && cur < cards.length-1) cur++; else if (dx > 0 && cur > 0) cur--; render(); }
});
window.addEventListener('resize', render);
render();

// ── Timeline: scroll to current ──────────────────────────────
const tlCurrent = document.getElementById('tl-current');
if (tlCurrent && document.getElementById('view-timeline') && !document.getElementById('view-timeline').classList.contains('hidden')) {
    setTimeout(() => tlCurrent.scrollIntoView({ behavior: 'smooth', block: 'center' }), 300);
}

// ── Auto-refresh cada 60 segundos ────────────────────────────
setTimeout(() => location.reload(), 60000);
})();
</script>
@endsection
