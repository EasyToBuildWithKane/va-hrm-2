import { useRef } from 'react';
import { motion, useScroll, useTransform } from 'framer-motion';
import { roadmapItems } from '@/data/roadmap';

export function Roadmap() {
    const ref = useRef<HTMLDivElement>(null);
    const { scrollYProgress } = useScroll({
        target: ref,
        offset: ['start end', 'end start'],
    });
    const lineScale = useTransform(scrollYProgress, [0, 0.85], [0, 1]);

    return (
        <section id="roadmap" className="scroll-mt-24 py-20 md:py-28" ref={ref}>
            <div className="mx-auto max-w-3xl px-4 md:px-8">
                <div className="mb-12 text-center">
                    <p className="text-sm font-bold tracking-[0.2em] text-accent">LỘ TRÌNH</p>
                    <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">Roadmap 2026–2027</h2>
                    <p className="mt-3 text-white/70">Hành trình từ chuyển đổi số đến doanh nghiệp ưu tiên AI — trọng tâm 2026–2027.</p>
                </div>
                <div className="relative">
                    <div className="absolute left-4 top-0 h-full w-0.5 origin-top bg-white/15 md:left-1/2 md:-ml-px">
                        <motion.div
                            className="h-full w-full origin-top bg-gradient-to-b from-primary to-accent"
                            style={{ scaleY: lineScale }}
                        />
                    </div>
                    <ul className="space-y-12">
                        {roadmapItems.map((item, i) => (
                            <motion.li
                                key={`${item.year}-${item.title}`}
                                className={`relative flex flex-col gap-4 pl-12 md:w-1/2 md:pl-0 ${
                                    i % 2 === 0 ? 'md:mr-auto md:pr-12 md:text-right' : 'md:ml-auto md:pl-12 md:text-left'
                                }`}
                                initial={{ opacity: 0, x: i % 2 === 0 ? -24 : 24 }}
                                whileInView={{ opacity: 1, x: 0 }}
                                viewport={{ once: true, margin: '-20px' }}
                                transition={{ duration: 0.5, delay: i * 0.05 }}
                            >
                                <span
                                    className={`absolute top-2 h-3 w-3 rounded-full ring-4 md:top-3 ${
                                        item.year === '2026' || item.year === '2027'
                                            ? 'bg-glow shadow-[0_0_12px_rgba(255,92,138,0.8)] ring-glow/25'
                                            : 'bg-primary ring-white/15'
                                    } ${
                                        i % 2 === 0 ? 'left-2.5 md:left-auto md:right-0 md:translate-x-1/2' : 'left-2.5 md:left-1/2 md:-translate-x-1/2'
                                    }`}
                                />
                                <span
                                    className={`text-sm font-bold ${
                                        item.year === '2026' || item.year === '2027' ? 'text-glow' : 'text-accent'
                                    }`}
                                >
                                    {item.year}
                                </span>
                                <h3 className="text-xl font-bold text-white">{item.title}</h3>
                                <p className="text-sm text-white/70">{item.description}</p>
                            </motion.li>
                        ))}
                    </ul>
                </div>
            </div>
        </section>
    );
}
