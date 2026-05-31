import { Children, useCallback, useEffect, useRef, useState } from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { cn } from '@/lib/utils';

interface SliderProps {
    children: React.ReactNode;
    /** Class chiều rộng mỗi slide (mặc định peek responsive). */
    slideClassName?: string;
    ariaLabel?: string;
    className?: string;
    /** Khoảng cách giữa slide (px) — khớp với gap-6 = 24. */
    gap?: number;
}

/**
 * Carousel/"swiper" tái sử dụng: scroll-snap ngang + vuốt cảm ứng + nút điều hướng + dots.
 * Không thêm dependency — dựa trên overflow-x + scroll-snap gốc của trình duyệt.
 */
export function Slider({ children, slideClassName, ariaLabel, className, gap = 24 }: SliderProps) {
    const slides = Children.toArray(children);
    const trackRef = useRef<HTMLDivElement>(null);
    const [active, setActive] = useState(0);

    const stepWidth = () => {
        const first = trackRef.current?.firstElementChild as HTMLElement | null;
        return (first?.offsetWidth ?? 1) + gap;
    };

    const onScroll = useCallback(() => {
        const el = trackRef.current;
        if (!el) return;
        setActive(Math.round(el.scrollLeft / stepWidth()));
    }, [gap]);

    const goTo = (i: number) => {
        const el = trackRef.current;
        if (!el) return;
        const idx = Math.max(0, Math.min(slides.length - 1, i));
        el.scrollTo({ left: idx * stepWidth(), behavior: 'smooth' });
    };

    // Đổi số slide (vd đổi bộ lọc) → reset về đầu.
    useEffect(() => {
        trackRef.current?.scrollTo({ left: 0 });
        setActive(0);
    }, [slides.length]);

    return (
        <div className={cn('relative', className)}>
            <div
                ref={trackRef}
                onScroll={onScroll}
                className="no-scrollbar flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth pb-2"
                aria-label={ariaLabel}
            >
                {slides.map((slide, i) => (
                    <div key={i} className={cn('shrink-0 snap-start', slideClassName ?? 'w-[82%] sm:w-[360px]')}>
                        {slide}
                    </div>
                ))}
            </div>

            {slides.length > 1 && (
                <div className="mt-6 flex items-center justify-between gap-4">
                    <div className="flex flex-wrap items-center gap-2">
                        {slides.map((_, i) => (
                            <button
                                key={i}
                                type="button"
                                onClick={() => goTo(i)}
                                aria-label={`Tới mục ${i + 1}`}
                                aria-current={active === i}
                                className={cn(
                                    'h-2 rounded-full transition-all duration-300',
                                    active === i ? 'w-6 bg-glow' : 'w-2 bg-white/25 hover:bg-white/45',
                                )}
                            />
                        ))}
                    </div>
                    <div className="flex shrink-0 gap-2">
                        <button
                            type="button"
                            onClick={() => goTo(active - 1)}
                            disabled={active === 0}
                            aria-label="Trước"
                            className="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white/70 transition hover:bg-white/15 hover:text-white disabled:cursor-not-allowed disabled:opacity-30"
                        >
                            <ChevronLeft className="h-5 w-5" />
                        </button>
                        <button
                            type="button"
                            onClick={() => goTo(active + 1)}
                            disabled={active === slides.length - 1}
                            aria-label="Tiếp theo"
                            className="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white/70 transition hover:bg-white/15 hover:text-white disabled:cursor-not-allowed disabled:opacity-30"
                        >
                            <ChevronRight className="h-5 w-5" />
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
}
