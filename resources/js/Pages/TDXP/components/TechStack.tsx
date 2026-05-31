import { useMemo, useState } from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { technologies } from '@/data/technologies';
import type { TechCategory } from '@/types/tdxp';
import { cn } from '@/lib/utils';
import { TechBackdrop } from './TechBackdrop';

const categoryLabels: Record<TechCategory, string> = {
    language: 'Ngôn ngữ',
    frontend: 'Frontend',
    backend: 'Backend',
    database: 'Cơ sở dữ liệu',
    devops: 'DevOps',
    cloud: 'Cloud',
    ai: 'AI',
};

const order: TechCategory[] = ['language', 'frontend', 'backend', 'database', 'devops', 'cloud', 'ai'];

type Filter = 'all' | TechCategory;

function TechLogo({ logo, name }: { logo: string; name: string }) {
    return (
        <span
            role="img"
            aria-label={name}
            className="tech-logo h-8 w-8 shrink-0"
            style={{ WebkitMaskImage: `url(/assets/tech-logos/${logo}.svg)`, maskImage: `url(/assets/tech-logos/${logo}.svg)` }}
        />
    );
}

export function TechStack() {
    const [filter, setFilter] = useState<Filter>('all');

    const filtered = useMemo(
        () => (filter === 'all' ? technologies : technologies.filter((t) => t.category === filter)),
        [filter],
    );

    const filters: { key: Filter; label: string }[] = [
        { key: 'all', label: 'Tất cả' },
        ...order.map((c) => ({ key: c as Filter, label: categoryLabels[c] })),
    ];

    return (
        <section id="stack" className="relative scroll-mt-24 overflow-hidden bg-secondary py-20 text-white md:py-28">
            <TechBackdrop />
            <div className="relative mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <div className="max-w-2xl">
                        <p className="text-sm font-bold tracking-[0.2em] text-accent">CÔNG NGHỆ SỬ DỤNG</p>
                        <h2 className="mt-3 text-3xl font-bold md:text-4xl">Hệ công nghệ vận hành</h2>
                        <p className="mt-3 text-white/60">
                            Những công nghệ chính thức đang chạy production — phát hiện trực tiếp từ mã nguồn dự án.
                        </p>
                    </div>
                </div>

                {/* Bộ lọc danh mục */}
                <div className="mb-10 flex flex-wrap gap-2">
                    {filters.map((f) => (
                        <button
                            key={f.key}
                            type="button"
                            onClick={() => setFilter(f.key)}
                            aria-pressed={filter === f.key}
                            className={cn(
                                'rounded-full border px-4 py-1.5 text-sm font-medium transition',
                                filter === f.key
                                    ? 'border-accent bg-accent/20 text-white'
                                    : 'border-white/10 text-white/60 hover:border-white/30 hover:text-white',
                            )}
                        >
                            {f.label}
                        </button>
                    ))}
                </div>

                {/* Lưới logo */}
                <motion.div layout className="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 lg:gap-4">
                    <AnimatePresence mode="popLayout">
                        {filtered.map((tech, i) => (
                            <motion.div
                                key={tech.id}
                                layout
                                initial={{ opacity: 0, scale: 0.85, rotate: -6 }}
                                animate={{ opacity: 1, scale: 1, rotate: 0 }}
                                exit={{ opacity: 0, scale: 0.85, rotate: 6 }}
                                transition={{ duration: 0.3, delay: Math.min(i * 0.02, 0.3), ease: [0.22, 1, 0.36, 1] }}
                                whileHover={{ y: -4, scale: 1.06, rotate: 0 }}
                                className={cn(
                                    'group relative flex flex-col gap-3 rounded-xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm',
                                    'transition hover:border-accent/50 hover:bg-white/10 hover:shadow-[var(--shadow-glow)]',
                                )}
                            >
                                {tech.inUse && (
                                    <span className="absolute right-3 top-3 h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]" title="Đang sử dụng" />
                                )}
                                <span className="text-white/70 transition group-hover:text-accent">
                                    <TechLogo logo={tech.logo} name={tech.name} />
                                </span>
                                <div>
                                    <p className="font-semibold leading-tight">{tech.name}</p>
                                    <p className="mt-0.5 text-xs text-white/40">{categoryLabels[tech.category]}</p>
                                </div>
                                <p className="line-clamp-2 text-xs text-white/45 opacity-0 transition group-hover:opacity-100">
                                    {tech.description}
                                </p>
                            </motion.div>
                        ))}
                    </AnimatePresence>
                </motion.div>

                {/* Carousel logo vô tận — 2 hàng chạy ngược chiều, phóng to khi rê chuột */}
                <div className="pause-on-hover relative mt-14 space-y-8 overflow-hidden border-t border-white/10 pt-10">
                    <div className="pointer-events-none absolute inset-y-0 left-0 z-10 w-24 bg-gradient-to-r from-secondary to-transparent" />
                    <div className="pointer-events-none absolute inset-y-0 right-0 z-10 w-24 bg-gradient-to-l from-secondary to-transparent" />
                    <div className="animate-marquee flex w-max items-center gap-12 text-white/40">
                        {[...technologies, ...technologies].map((tech, idx) => (
                            <span
                                key={`${tech.id}-${idx}`}
                                className="inline-flex transition-transform duration-300 hover:scale-125 hover:text-white"
                            >
                                <TechLogo logo={tech.logo} name={tech.name} />
                            </span>
                        ))}
                    </div>
                    <div className="animate-marquee-slow flex w-max items-center gap-12 text-white/30 [animation-direction:reverse]">
                        {[...technologies].reverse().concat([...technologies].reverse()).map((tech, idx) => (
                            <span
                                key={`rev-${tech.id}-${idx}`}
                                className="inline-flex transition-transform duration-300 hover:scale-125 hover:text-white"
                            >
                                <TechLogo logo={tech.logo} name={tech.name} />
                            </span>
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}
