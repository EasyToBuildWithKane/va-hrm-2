import CountUp from 'react-countup';
import { useInView } from 'react-intersection-observer';
import { motion, useReducedMotion } from 'framer-motion';
import { impactMetrics } from '@/data/metrics';

function MetricCard({
    value,
    suffix,
    prefix,
    label,
    decimals,
    index,
    inView,
    reduce,
}: {
    value: number;
    suffix?: string;
    prefix?: string;
    label: string;
    decimals?: number;
    index: number;
    inView: boolean;
    reduce: boolean;
}) {
    return (
        <motion.div
            className="glass-card group relative overflow-hidden rounded-2xl p-6 text-center transition hover:border-white/25 hover:shadow-[0_0_36px_rgba(255,92,138,0.22)]"
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, delay: Math.min(index * 0.08, 0.4), ease: [0.22, 1, 0.36, 1] }}
        >
            <span
                className="animate-glow pointer-events-none absolute -right-10 -top-10 h-24 w-24 rounded-full blur-2xl opacity-0 transition group-hover:opacity-100"
                style={{ background: 'radial-gradient(circle, rgba(255,92,138,0.4), transparent 70%)' }}
            />
            <p className="relative bg-gradient-to-br from-white to-glow bg-clip-text text-4xl font-bold tabular-nums text-transparent md:text-5xl">
                {prefix}
                {!inView ? (
                    '0'
                ) : reduce ? (
                    value.toLocaleString('en-US', { minimumFractionDigits: decimals ?? 0 })
                ) : (
                    <CountUp end={value} duration={2.2} decimals={decimals ?? 0} separator="," />
                )}
                {suffix}
            </p>
            <p className="relative mt-2 text-sm font-medium text-white/65">{label}</p>
        </motion.div>
    );
}

export function ImpactMetrics() {
    const { ref, inView } = useInView({ triggerOnce: true, threshold: 0.2 });
    const reduce = useReducedMotion() ?? false;

    return (
        <section id="impact" className="bg-aurora scroll-mt-24 py-20 md:py-28" ref={ref}>
            <div className="relative mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-12 max-w-2xl">
                    <p className="text-sm font-bold tracking-[0.25em] text-accent">THÀNH TỰU</p>
                    <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">Thành tựu nổi bật</h2>
                    <p className="mt-3 text-white/70">
                        Số liệu phản ánh năng lực triển khai và giá trị mang lại cho hệ thống.
                    </p>
                </div>
                <div className="grid grid-cols-2 gap-4 md:grid-cols-3 lg:gap-6">
                    {impactMetrics.map((m, i) => (
                        <MetricCard
                            key={m.id}
                            value={m.value}
                            suffix={m.suffix}
                            prefix={m.prefix}
                            label={m.label}
                            decimals={m.decimals}
                            index={i}
                            inView={inView}
                            reduce={reduce}
                        />
                    ))}
                </div>
            </div>
        </section>
    );
}
