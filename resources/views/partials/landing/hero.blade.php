@php
    $heroFirst = $featuredCarouselData->first();
@endphp
<section class="lp-hero"
         @if($heroFirst)
             style="background-image: url('{{ $heroFirst['cover'] ?: '/error.png' }}');"
             x-data="landingHero(@js($featuredCarouselData))"
             @mouseenter="paused = true"
             @mouseleave="paused = false"
         @endif>

    @if($featuredCarouselData->isNotEmpty())
        <template x-for="(novel, index) in novels" :key="'hero-bg-'+novel.id">
            <div class="lp-hero-bg"
                 x-show="activeIndex === index"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-400"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 :style="`background-image: url('${novel.cover || '/error.png'}');`"></div>
        </template>
    @else
        <div class="lp-hero-bg lp-hero-bg--fallback"></div>
    @endif

    <div class="lp-hero-shade"></div>

    <div class="lp-hero-inner">
        <div class="lp-hero-copy">
            <span class="lp-badge">EDITOR CHOICE</span>

            @if($featuredCarouselData->isNotEmpty())
                <h1 class="lp-hero-title" x-text="current.title">{{ $featuredNovels->first()->title }}</h1>
                <p class="lp-hero-desc" x-text="current.description">
                    {{ Str::limit(strip_tags($featuredNovels->first()->description ?? ''), 180) }}
                </p>
                <a :href="current.read_url" class="lp-btn-gold">
                    START READING <span aria-hidden="true">+</span>
                </a>
            @else
                <h1 class="lp-hero-title">QUOROS</h1>
                <p class="lp-hero-desc">Temukan novel yang membuatmu lupa waktu — curated stories, experience membaca yang nyaman.</p>
                <a href="{{ route('novels.updated') }}" class="lp-btn-gold">
                    START READING <span aria-hidden="true">+</span>
                </a>
            @endif
        </div>

        @if($featuredCarouselData->count() > 1)
            <div class="lp-hero-dots" role="tablist" aria-label="Featured novels">
                <template x-for="(_, index) in novels" :key="'dot-'+index">
                    <button type="button"
                            class="lp-dot"
                            :class="{ 'is-active': activeIndex === index }"
                            @click="goTo(index)"
                            :aria-label="'Slide ' + (index + 1)"
                            :aria-selected="(activeIndex === index).toString()"></button>
                </template>
            </div>
        @endif
    </div>
</section>
