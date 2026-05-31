import { motion, useReducedMotion } from 'framer-motion';
import { aiCapabilities } from '@/data/aiLab';
import { getLucideIcon } from '@/lib/lucide';
import { ParticleField } from './ParticleField';

// "Khối dữ liệu" trôi nổi nhẹ trên nền — gợi cảm giác mạng lưới AI.
const dataChips = ['RAG', 'LLM', 'Vector DB', 'Agent', 'Prompt', 'Fine-tune', 'Embedding', 'MCP'];

export function AILab() {
    const reduce = useReducedMotion();

    return (
        <section id="ai" className="relative scroll-mt-24 overflow-hidden bg-secondary py-20 text-white md:py-28">
            {/* Nền neural network (particle) + lưới */}
            <div className="absolute inset-0">
                <ParticleField className="absolute inset-0 h-full w-full" lineRgb="225,29,72" density={1} />
                <div className="absolute inset-0 bg-gradient-to-t from-secondary via-transparent to-primary/10" />
            </div>

            {/* Data blocks bay nhẹ */}
            {!reduce && (
                <div className="pointer-events-none absolute inset-0 hidden md:block" aria-hidden>
                    {dataChips.map((chip, i) => (
                        <motion.span
                            key={chip}
                            className="glass absolute rounded-lg px-3 py-1 text-xs font-semibold text-white/70"
                            style={{ left: `${8 + (i * 11) % 84}%`, top: `${15 + (i * 23) % 70}%` }}
                            animate={{ y: [0, -16, 0], opacity: [0.4, 0.85, 0.4] }}
                            transition={{ duration: 5 + (i % 4), repeat: Infinity, ease: 'easeInOut', delay: i * 0.4 }}
                        >
                            {chip}
                        </motion.span>
                    ))}
                </div>
            )}

            <div className="relative mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-12 max-w-2xl">
                    <p className="text-sm font-bold tracking-[0.25em] text-accent">ĐỔI MỚI SÁNG TẠO</p>
                    <h2 className="mt-3 text-3xl font-bold md:text-4xl">AI &amp; Innovation Lab</h2>
                    <p className="mt-3 text-white/60">
                        Nghiên cứu &amp; phát triển nội bộ — từ trợ lý lập trình, tác tử AI đến tri thức đồ thị doanh nghiệp.
                    </p>
                </div>
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {aiCapabilities.map((cap, i) => {
                        const Icon = getLucideIcon(cap.icon);
                        return (
                            <motion.div
                                key={cap.id}
                                className="glass-card group relative overflow-hidden rounded-2xl p-6"
                                initial={{ opacity: 0, y: 20, scale: 0.96 }}
                                whileInView={{ opacity: 1, y: 0, scale: 1 }}
                                viewport={{ once: true, margin: '-40px' }}
                                transition={{ delay: Math.min(i * 0.08, 0.4), duration: 0.5, ease: [0.22, 1, 0.36, 1] }}
                                whileHover={{ scale: 1.03, borderColor: 'rgba(225,29,72,0.5)' }}
                            >
                                <span
                                    className="animate-glow pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full blur-2xl opacity-0 transition group-hover:opacity-100"
                                    style={{ background: 'radial-gradient(circle, rgba(255,92,138,0.4), transparent 70%)' }}
                                />
                                <Icon className="relative h-8 w-8 text-accent" />
                                <h3 className="relative mt-4 text-lg font-semibold text-white">{cap.title}</h3>
                                <p className="relative mt-2 text-sm text-white/60">{cap.description}</p>
                            </motion.div>
                        );
                    })}
                </div>
            </div>
        </section>
    );
}
