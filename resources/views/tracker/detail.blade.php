@extends('layouts.app')
@section('title', 'Tracker Detallado')

@section('content')
@auth
<div class="max-w-5xl mx-auto px-4 pt-4 pb-0">
    <form method="POST" action="{{ route('tracker.settings.update') }}" id="settings-form">
        @csrf @method('PATCH')
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 bg-white border border-muci-gray-light rounded-xl px-4 py-3">
            <span class="text-[.6rem] font-bold uppercase tracking-widest text-muci-gray">Vista del timeline</span>
            <label class="flex items-center gap-1.5 cursor-pointer text-xs text-muci-dark">
                <input type="radio" name="image_layout" value="B"
                       class="accent-muci-green"
                       onchange="document.getElementById('settings-form').submit()"
                       {{ $imageLayout === 'B' ? 'checked' : '' }}>
                Layout B <span class="text-muci-gray">(imagen lateral)</span>
            </label>
            <label class="flex items-center gap-1.5 cursor-pointer text-xs text-muci-dark">
                <input type="radio" name="image_layout" value="C"
                       class="accent-muci-green"
                       onchange="document.getElementById('settings-form').submit()"
                       {{ $imageLayout === 'C' ? 'checked' : '' }}>
                Layout C <span class="text-muci-gray">(thumbnails en card)</span>
            </label>
            <div class="border-l border-muci-gray-light pl-6 flex items-center gap-6">
                <label class="flex items-center gap-1.5 cursor-pointer text-xs text-muci-dark">
                    <input type="hidden" name="hide_images_mobile" value="0">
                    <input type="checkbox" name="hide_images_mobile" value="1"
                           class="accent-muci-green"
                           onchange="document.getElementById('settings-form').submit()"
                           {{ $hideImagesMobile ? 'checked' : '' }}>
                    Ocultar imágenes en móvil
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer text-xs text-muci-dark">
                    <input type="hidden" name="auto_refresh" value="0">
                    <input type="checkbox" name="auto_refresh" value="1"
                           class="accent-muci-green"
                           onchange="document.getElementById('settings-form').submit()"
                           {{ $autoRefresh ? 'checked' : '' }}>
                    Auto-refresh <span class="text-muci-gray">(cada 60s)</span>
                </label>
            </div>
        </div>
    </form>
</div>
@endauth
<div class="max-w-5xl mx-auto px-4 py-6">

    {{-- Tabs de etapas --}}
    <div class="flex gap-2 mb-6 flex-wrap">

        {{-- Botones de tab --}}
        <div class="flex gap-2 flex-wrap w-full mb-4">
            @foreach($stages as $stage)
            <button
                onclick="document.getElementById('stage-{{ $stage->id }}').scrollIntoView({behavior:'smooth',block:'start'})"
                class="text-xs font-semibold px-4 py-2 rounded-full border border-muci-gray-light text-muci-dark hover:bg-muci-green hover:text-white hover:border-muci-green transition">
                Etapa {{ $loop->iteration }} · {{ $stage->year }}
                <span class="ml-1 font-normal opacity-70">{{ $stage->progressPct() }}%</span>
            </button>
            @endforeach
        </div>

        {{-- Contenido por etapa --}}
        @foreach($stages as $stage)
        <div id="stage-{{ $stage->id }}" class="w-full mb-8">

            {{-- Header de etapa --}}
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-muci-green-mid mb-0.5">
                        Etapa {{ $loop->iteration }}
                    </p>
                    <h2 class="text-lg font-bold text-black">{{ $stage->name }}</h2>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-black">{{ $stage->progressPct() }}%</div>
                    <div class="w-28 h-1.5 bg-muci-green-pale rounded-full overflow-hidden mt-1">
                        <div class="h-full bg-muci-green rounded-full" style="width:{{ $stage->progressPct() }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Secciones --}}
            <div class="space-y-3">
                @foreach($stage->sections as $section)
                <details class="group bg-white border border-muci-gray-light rounded-xl overflow-hidden"
                    {{ $section->name === 'Mayo 2026' ? 'open' : '' }}>

                    <summary class="flex items-center justify-between px-5 py-3.5 cursor-pointer select-none list-none hover:bg-muci-green-pale/30 transition">
                        <div class="flex items-center gap-3">
                            <span class="text-[0.6rem] font-bold uppercase tracking-widest text-muci-green">▶</span>
                            <span class="font-semibold text-black text-sm">{{ $section->name }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            @php $spct = $section->progressPct(); @endphp
                            <div class="w-20 h-1.5 bg-muci-green-pale rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $spct > 0 ? 'bg-muci-green' : 'bg-muci-gray-light' }}"
                                     style="width:{{ $spct }}%"></div>
                            </div>
                            <span class="text-xs font-semibold {{ $spct > 0 ? 'text-muci-green' : 'text-muci-gray' }}">
                                {{ $spct }}%
                            </span>
                        </div>
                    </summary>

                    <div class="px-5 pb-4 pt-1 border-t border-muci-gray-light/50">

                        {{-- Items --}}
                        @foreach($section->items as $item)
                        <div class="py-2.5 border-b border-muci-green-pale/50 last:border-0">
                            <div class="flex items-start gap-3">

                                {{-- Badge tipo --}}
                                <span class="mt-0.5 text-[0.55rem] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded
                                    {{ $item->type === 'objetivo' ? 'bg-muci-green/10 text-muci-green' : 'bg-muci-orange/10 text-muci-orange' }}">
                                    {{ $item->type }}
                                </span>

                                {{-- Modo lectura / edición --}}
                                @auth
                                {{-- MODO EDICIÓN --}}
                                <form method="POST" action="{{ route('items.update', $item) }}" class="flex-1 flex items-start gap-2">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="completed" value="0">
                                    <input type="checkbox" name="completed" value="1"
                                           {{ $item->isCompleted ? 'checked' : '' }}
                                           onchange="this.form.submit()"
                                           class="mt-1 w-4 h-4 accent-muci-green cursor-pointer">
                                    <div class="flex-1">
                                        <span class="text-sm {{ $item->isCompleted ? 'line-through text-muci-gray' : 'text-black' }}">
                                            {{ $item->text }}
                                        </span>
                                        @if($item->latestComment)
                                        <p class="text-xs text-muci-gray mt-0.5 italic">{{ $item->latestComment }}</p>
                                        @endif
                                    </div>
                                </form>
                                @else
                                {{-- MODO LECTURA --}}
                                <div class="flex-1 flex items-start gap-2">
                                    <div class="mt-1 w-4 h-4 rounded border-2 flex items-center justify-center flex-shrink-0
                                        {{ $item->isCompleted ? 'bg-muci-green border-muci-green' : 'border-muci-gray-light' }}">
                                        @if($item->isCompleted)
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm {{ $item->isCompleted ? 'line-through text-muci-gray' : 'text-black' }}">
                                            {{ $item->text }}
                                        </span>
                                        @if($item->latestComment)
                                        <p class="text-xs text-muci-gray mt-0.5 italic">{{ $item->latestComment }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endauth
                            </div>{{-- fin flex items-start --}}

                            {{-- Zona de imágenes (solo auth) --}}
                            @auth
                            <div class="ml-8 mt-2">
                                <div class="flex flex-wrap gap-2 items-center">
                                    @foreach($item->images as $img)
                                    <div class="relative group">
                                        <img src="{{ \Storage::disk('public')->url($img->path) }}"
                                             alt=""
                                             class="w-20 h-20 object-cover rounded-lg border border-muci-gray-light cursor-pointer"
                                             data-gallery-images="{{ json_encode($item->images->map(fn($i) => \Storage::disk('public')->url($i->path))->values()->all()) }}"
                                             data-gallery-index="{{ $loop->index }}"
                                             onclick="openGallery(JSON.parse(this.dataset.galleryImages), parseInt(this.dataset.galleryIndex))">
                                        <form method="POST" action="{{ route('images.destroy', [$item, $img]) }}"
                                              class="absolute top-1 right-1"
                                              onsubmit="return confirm('¿Eliminar imagen?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="w-5 h-5 bg-white/90 rounded-full text-xs font-bold text-muci-gray hover:text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-sm">
                                                ×
                                            </button>
                                        </form>
                                    </div>
                                    @endforeach

                                    {{-- Botón agregar --}}
                                    <form method="POST" action="{{ route('images.store', $item) }}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <label class="w-20 h-20 flex items-center justify-center border-2 border-dashed border-muci-gray-light rounded-lg cursor-pointer hover:border-muci-green hover:bg-muci-green-pale/30 transition text-muci-gray hover:text-muci-green text-2xl leading-none select-none">
                                            +
                                            <input type="file" name="image" accept="image/*" class="hidden"
                                                   onchange="this.closest('form').submit()">
                                        </label>
                                    </form>
                                </div>
                            </div>
                            @endauth
                        </div>{{-- fin py-2.5 --}}
                        @endforeach

                        {{-- Agregar ítem (solo editora) --}}
                        @auth
                        <form method="POST" action="{{ route('items.store', $section) }}" class="mt-3 flex gap-2">
                            @csrf
                            <select name="type" class="text-xs border border-muci-gray-light rounded-lg px-2 py-1.5 text-muci-dark focus:outline-none focus:border-muci-green">
                                <option value="objetivo">Objetivo</option>
                                <option value="meta">Meta</option>
                            </select>
                            <input type="text" name="text" placeholder="Agregar ítem..."
                                   class="flex-1 text-sm border border-muci-gray-light rounded-lg px-3 py-1.5 focus:outline-none focus:border-muci-green"
                                   required maxlength="500">
                            <button type="submit"
                                    class="text-xs font-semibold px-3 py-1.5 bg-muci-green text-white rounded-lg hover:bg-muci-green-mid transition">
                                + Agregar
                            </button>
                        </form>
                        @endauth

                    </div>
                </details>
                @endforeach

                {{-- Agregar sección (solo editora) --}}
                @auth
                <form method="POST" action="{{ route('sections.store', $stage) }}"
                      class="flex gap-2 mt-2 p-3 border border-dashed border-muci-gray-light rounded-xl">
                    @csrf
                    <input type="text" name="name" placeholder="Nueva sección (ej: Enero 2027)..."
                           class="flex-1 text-sm border border-muci-gray-light rounded-lg px-3 py-1.5 focus:outline-none focus:border-muci-green"
                           required maxlength="100">
                    <button type="submit"
                            class="text-xs font-semibold px-3 py-1.5 border border-muci-green text-muci-green rounded-lg hover:bg-muci-green hover:text-white transition">
                        + Sección
                    </button>
                </form>
                @endauth

            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection
