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
    inView,
    reduce,
}: {
    value: number;
    suffix?: string;
    prefix?: string;
    label: string;
    decimals?: number;
    inView: boolean;
    reduce: boolean;
}) {
    return (
        <motion.div
            className="group relative overflow-hidden rounded-2xl border border-secondary/10 bg-white p-6 shadow-sm transition hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5 }}
        >
            <div className="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 transition group-hover:opacity-100" />
            <p className="relative text-3xl font-bold tabular-nums text-secondary md:text-4xl">
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
            <p className="relative mt-2 text-sm text-secondary/60">{label}</p>
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
                    <h2 className="text-3xl font-bold text-white md:text-4xl">Tác động thực tế</h2>
                    <p className="mt-3 text-white/70">
                        Số liệu phản ánh năng lực triển khai và giá trị mang lại cho doanh nghiệp.
                    </p>
                </div>
                <div className="grid grid-cols-2 gap-4 md:grid-cols-3 lg:gap-6">
                    {impactMetrics.map((m) => (
                        <MetricCard
                            key={m.id}
                            value={m.value}
                            suffix={m.suffix}
                            prefix={m.prefix}
                            label={m.label}
                            decimals={m.decimals}
                            inView={inView}
                            reduce={reduce}
                        />
                    ))}
                </div>
            </div>
        </section>
    );
}
