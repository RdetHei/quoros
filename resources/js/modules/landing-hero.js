export function landingHero(initialNovels) {
    return {
        activeSlide: 0,
        novels: initialNovels,
        paused: false,
        timer: null,
        get current() { return this.novels[this.activeSlide] || {}; },
        get activeIndex() { return this.activeSlide; },
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
        prev() {
            this.activeSlide = (this.activeSlide - 1 + this.slideCount) % this.slideCount;
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
        stackStyle(index) {
            const total = this.slideCount;
            const current = this.activeSlide;
            let offset = index - current;
            if (offset > total / 2) offset -= total;
            if (offset < -total / 2) offset += total;

            if (offset === 0) {
                return `transform: translate(-50%, -50%) rotateY(0deg) rotateZ(0deg) translateX(0) translateY(0) scale(1); z-index: 50; opacity: 1;`;
            } else if (Math.abs(offset) === 1) {
                const dir = offset > 0 ? 1 : -1;
                return `transform: translate(-50%, -50%) rotateY(${dir * -14}deg) rotateZ(${dir * 3}deg) translateX(${dir * 70}px) translateY(18px) scale(0.88); z-index: ${30 - dir}; opacity: 0.85;`;
            } else if (Math.abs(offset) === 2) {
                const dir = offset > 0 ? 1 : -1;
                return `transform: translate(-50%, -50%) rotateY(${dir * -22}deg) rotateZ(${dir * 5}deg) translateX(${dir * 130}px) translateY(36px) scale(0.76); z-index: ${15 - dir}; opacity: 0.55;`;
            } else {
                const dir = offset > 0 ? 1 : -1;
                return `transform: translate(-50%, -50%) rotateY(${dir * -28}deg) rotateZ(${dir * 7}deg) translateX(${dir * 180}px) translateY(52px) scale(0.64); z-index: 5; opacity: 0; pointer-events: none;`;
            }
        },
    };
}
