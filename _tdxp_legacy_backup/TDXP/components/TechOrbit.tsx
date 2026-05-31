import { motion, useMotionValue, useReducedMotion, useSpring } from 'framer-motion';
import { orbitRings } from '@/data/technologies';
import { cn } from '@/lib/utils';
import { useRef } from 'react';

const ringConfig = [
    { key: 'languages' as const, radius: 72, duration: 28, reverse: false },
    { key: 'frameworks' as const, radius: 110, duration: 36, reverse: true },
    { key: 'infrastructure' as const, radius: 148, duration: 44, reverse: false },
    { key: 'ai' as const, radius: 186, duration: 52, reverse: true },
];

function OrbitNode({ label, angle, radius, ringDuration, reverse }: {
    label: string;
    angle: number;
    radius: number;
    ringDuration: number;
    reverse: boolean;
}) {
    const ref = useRef<HTMLDivElement>(null);
    const reduce = useReducedMotion();
    const mx = useMotionValue(0);
    const my = useMotionValue(0);
    const sx = useSpring(mx, { stiffness: 200, damping: 20 });
    const sy = useSpring(my, { stiffness: 200, damping: 20 });

    return (
        <motion.div
            className="absolute left-1/2 top-1/2 h-0 w-0"
            animate={reduce ? undefined : { rotate: reverse ? -360 : 360 }}
            transition={reduce ? undefined : { duration: ringDuration, repeat: Infinity, ease: 'linear' }}
        >
            <motion.div
                ref={ref}
                className="absolute -translate-x-1/2 -translate-y-1/2"
                style={{
                    left: Math.cos(angle) * radius,
                    top: Math.sin(angle) * radius,
                    x: sx,
                    y: sy,
                }}
                onMouseMove={(e) => {
                    const el = ref.current;
                    if (!el) return;
                    const rect = el.getBoundingClientRect();
                    const cx = rect.left + rect.width / 2;
                    const cy = rect.top + rect.height / 2;
                    mx.set((e.clientX - cx) * 0.15);
                    my.set((e.clientY - cy) * 0.15);
                }}
                onMouseLeave={() => {
                    mx.set(0);
                    my.set(0);
                }}
            >
                <span
                    className={cn(
                        'flex cursor-default items-center whitespace-nowrap rounded-full border border-white/20',
                        'bg-white/90 px-2.5 py-1 text-[10px] font-semibold text-secondary shadow-lg shadow-primary/10',
                        'backdrop-blur-md transition hover:border-primary/40 hover:shadow-primary/25 md:px-3 md:text-xs',
                    )}
                >
                    {label}
                </span>
            </motion.div>
        </motion.div>
    );
}

export function TechOrbit() {
    const reduce = useReducedMotion();
    return (
        <div className="relative mx-auto flex aspect-square w-full max-w-[420px] items-center justify-center md:max-w-[480px]">
            <motion.div
                className="absolute inset-8 rounded-full bg-gradient-to-br from-primary/20 via-accent/10 to-transparent blur-3xl"
                animate={reduce ? undefined : { opacity: [0.5, 0.85, 0.5], scale: [0.95, 1.05, 0.95] }}
                transition={reduce ? undefined : { duration: 6, repeat: Infinity, ease: 'easeInOut' }}
            />
            {ringConfig.map(({ key, radius, duration, reverse }) => (
                <div
                    key={key}
                    className="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full border border-dashed border-secondary/10"
                    style={{ width: radius * 2, height: radius * 2 }}
                />
            ))}
            {ringConfig.map(({ key, radius, duration, reverse }) => {
                const items = orbitRings[key];
                return items.map((label, i) => {
                    const angle = (i / items.length) * Math.PI * 2;
                    return (
                        <OrbitNode
                            key={`${key}-${label}`}
                            label={label}
                            angle={angle}
                            radius={radius}
                            ringDuration={duration}
                            reverse={reverse}
                        />
                    );
                });
            })}
            <motion.div
                className="relative z-10 flex h-28 w-28 flex-col items-center justify-center rounded-full border border-primary/30 bg-gradient-to-br from-primary to-secondary p-4 text-center shadow-2xl shadow-primary/30 md:h-32 md:w-32"
                animate={reduce ? undefined : { y: [0, -6, 0] }}
                transition={reduce ? undefined : { duration: 4, repeat: Infinity, ease: 'easeInOut' }}
            >
                <span className="text-[10px] font-medium uppercase tracking-wider text-white/80 md:text-xs">
                    Lõi
                </span>
                <span className="text-xs font-bold leading-tight text-white md:text-sm">
                    Phòng
                    <br />
                    Công Nghệ
                </span>
            </motion.div>
            <span className="sr-only">Hoạt ảnh quỹ đạo hệ sinh thái công nghệ</span>
        </div>
    );
}
