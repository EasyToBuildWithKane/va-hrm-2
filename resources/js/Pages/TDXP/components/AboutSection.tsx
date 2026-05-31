import { motion } from 'framer-motion';
import { Target, Eye } from 'lucide-react';
import { about } from '@/data/about';
import { getLucideIcon } from '@/lib/lucide';

const reveal = {
    hidden: { opacity: 0, y: 28 },
    show: (i: number) => ({
        opacity: 1,
        y: 0,
        transition: { duration: 0.55, delay: i * 0.08, ease: [0.22, 1, 0.36, 1] },
    }),
};

export function AboutSection() {
    return (
        <section id="about" className="scroll-mt-24 py-20 md:py-28">
            <div className="mx-auto max-w-7xl px-4 md:px-8">
                <motion.div
                    className="mb-14 max-w-2xl"
                    initial={{ opacity: 0, y: 20 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, margin: '-60px' }}
                    transition={{ duration: 0.5 }}
                >
                    <p className="text-sm font-bold tracking-[0.25em] text-accent">{about.eyebrow}</p>
                    <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">{about.heading}</h2>
                    <p className="mt-4 text-lg leading-relaxed text-white/70">{about.intro}</p>
                </motion.div>

                <div className="grid items-start gap-10 lg:grid-cols-2 lg:gap-16">
                    {/* Cột trái — trụ minh hoạ (Data Center / AI / Cloud / EdTech) */}
                    <div className="grid grid-cols-2 gap-4 sm:gap-6">
                        {about.pillars.map((pillar, i) => {
                            const Icon = getLucideIcon(pillar.icon);
                            return (
                                <motion.div
                                    key={pillar.id}
                                    className="glass-card tech-border group relative overflow-hidden rounded-2xl p-6 text-center"
                                    custom={i}
                                    variants={reveal}
                                    initial="hidden"
                                    whileInView="show"
                                    viewport={{ once: true, margin: '-40px' }}
                                    whileHover={{ y: -6 }}
                                >
                                    <span
                                        className="animate-glow pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full blur-2xl"
                                        style={{ background: 'radial-gradient(circle, rgba(255,92,138,0.35), transparent 70%)' }}
                                    />
                                    <span className="relative inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-glow">
                                        <Icon className="h-7 w-7" />
                                    </span>
                                    <p className="relative mt-4 text-lg font-bold text-white">{pillar.label}</p>
                                    <p className="relative mt-1 text-sm text-white/55">{pillar.caption}</p>
                                </motion.div>
                            );
                        })}
                    </div>

                    {/* Cột phải — Sứ mệnh / Tầm nhìn / Giá trị cốt lõi */}
                    <div className="space-y-6">
                        {[
                            { icon: Target, ...about.mission },
                            { icon: Eye, ...about.vision },
                        ].map((block, i) => {
                            const Icon = block.icon;
                            return (
                                <motion.div
                                    key={block.title}
                                    className="rounded-2xl border-l-2 border-primary/70 bg-white/[0.04] p-6"
                                    custom={i}
                                    variants={reveal}
                                    initial="hidden"
                                    whileInView="show"
                                    viewport={{ once: true, margin: '-40px' }}
                                >
                                    <div className="flex items-center gap-3">
                                        <Icon className="h-6 w-6 text-accent" />
                                        <h3 className="text-xl font-bold text-white">{block.title}</h3>
                                    </div>
                                    <p className="mt-3 leading-relaxed text-white/70">{block.description}</p>
                                </motion.div>
                            );
                        })}

                        <motion.div
                            custom={2}
                            variants={reveal}
                            initial="hidden"
                            whileInView="show"
                            viewport={{ once: true, margin: '-40px' }}
                        >
                            <h3 className="mb-4 text-sm font-bold tracking-[0.2em] text-white/50">GIÁ TRỊ CỐT LÕI</h3>
                            <div className="grid gap-4 sm:grid-cols-2">
                                {about.values.map((v) => {
                                    const Icon = getLucideIcon(v.icon);
                                    return (
                                        <div
                                            key={v.id}
                                            className="rounded-xl border border-white/10 bg-white/[0.03] p-4 transition hover:border-white/25 hover:bg-white/[0.06]"
                                        >
                                            <Icon className="h-5 w-5 text-glow" />
                                            <p className="mt-3 font-semibold text-white">{v.title}</p>
                                            <p className="mt-1 text-sm text-white/60">{v.description}</p>
                                        </div>
                                    );
                                })}
                            </div>
                        </motion.div>
                    </div>
                </div>
            </div>
        </section>
    );
}
