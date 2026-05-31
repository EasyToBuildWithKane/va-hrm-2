import { useRef } from 'react';
import { motion, useReducedMotion, useScroll, useTransform } from 'framer-motion';
import { ChevronDown } from 'lucide-react';
import { Button } from '@/components/ui/Button';
import { site } from '@/data/site';
import { ParticleField } from './ParticleField';

const container = {
    hidden: { opacity: 0 },
    show: { opacity: 1, transition: { staggerChildren: 0.12, delayChildren: 0.1 } },
};

const item = {
    hidden: { opacity: 0, y: 24 },
    show: { opacity: 1, y: 0, transition: { duration: 0.6, ease: [0.22, 1, 0.36, 1] } },
};

const scrollTo = (id: string) => document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });

export function HeroSection() {
    const ref = useRef<HTMLElement>(null);
    const reduce = useReducedMotion();
    const { scrollYProgress } = useScroll({ target: ref, offset: ['start start', 'end start'] });
    // Parallax nhẹ: nền trôi chậm, nội dung mờ dần khi cuộn xuống.
    const bgY = useTransform(scrollYProgress, [0, 1], [0, 140]);
    const contentY = useTransform(scrollYProgress, [0, 1], [0, 80]);
    const contentOpacity = useTransform(scrollYProgress, [0, 0.6], [1, 0]);
    const { hero } = site;

    return (
        <section
            id="hero"
            ref={ref}
            className="relative flex min-h-[640px] flex-col justify-center overflow-hidden pt-28 pb-20 md:min-h-screen"
        >
            {/* Lớp nền animation: particle network + circuit + lưới + quầng sáng */}
            <motion.div className="pointer-events-none absolute inset-0" style={reduce ? undefined : { y: bgY }}>
                <ParticleField className="absolute inset-0 h-full w-full" />
                <div className="bg-mesh-animated absolute inset-0 opacity-40" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_30%,rgba(8,28,44,0.55)_100%)]" />
                <div
                    className="animate-glow absolute -top-24 right-[-10%] h-[28rem] w-[28rem] rounded-full blur-3xl"
                    style={{ background: 'radial-gradient(circle, rgba(255,92,138,0.22), transparent 70%)' }}
                />
                <div
                    className="animate-glow absolute bottom-[-12%] left-[-8%] h-96 w-96 rounded-full blur-3xl"
                    style={{ background: 'radial-gradient(circle, rgba(36,99,156,0.28), transparent 70%)' }}
                />
            </motion.div>

            <motion.div
                className="relative mx-auto w-full max-w-4xl px-4 text-center md:px-8"
                style={reduce ? undefined : { y: contentY, opacity: contentOpacity }}
                variants={container}
                initial="hidden"
                animate="show"
            >
                <motion.p variants={item} className="text-sm font-bold tracking-[0.3em] text-accent">
                    {hero.eyebrow}
                </motion.p>
                <motion.h1
                    variants={item}
                    className="mx-auto mt-5 max-w-3xl bg-gradient-to-br from-white via-white to-glow bg-clip-text text-4xl font-bold leading-[1.1] tracking-tight text-transparent md:text-6xl lg:text-7xl"
                >
                    {hero.title}
                </motion.h1>
                <motion.p variants={item} className="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-white/70">
                    {hero.subtitle.join(' ')}
                </motion.p>
                <motion.div variants={item} className="mt-10 flex flex-wrap justify-center gap-4">
                    {hero.ctas.map((cta) => (
                        <Button
                            key={cta.target}
                            size="lg"
                            variant={cta.variant ?? 'primary'}
                            onClick={() => scrollTo(cta.target)}
                        >
                            {cta.label}
                        </Button>
                    ))}
                </motion.div>
            </motion.div>

            {/* Gợi ý cuộn */}
            <motion.button
                type="button"
                onClick={() => scrollTo('about')}
                aria-label="Cuộn xuống"
                className="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/50 transition hover:text-white"
                animate={reduce ? undefined : { y: [0, 8, 0] }}
                transition={{ duration: 1.8, repeat: Infinity, ease: 'easeInOut' }}
            >
                <ChevronDown className="h-7 w-7" />
            </motion.button>
        </section>
    );
}
