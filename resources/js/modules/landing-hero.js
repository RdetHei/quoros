export function landingHero(initialNovels) {
    return {
        activeSlide: 0,
        novels: initialNovels,
        paused: false,
        timer: null,
        get current() { return this.novels[this.activeSlide] || {}; },
        get slideCount() { return this.novels.length; },
        init() {
            if (this.slideCount > 1) this.startTimer();
        },
        goTo(index) {
            this.activeSlide = index;
            this.resetTimer();
        },
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.slideCount;
        },
        startTimer() {
            this.timer = setInterval(() => {
                if (!this.paused) this.next();
            }, 6000);
        },
        resetTimer() {
            clearInterval(this.timer);
            if (this.slideCount > 1) this.startTimer();
        },
    };
}
