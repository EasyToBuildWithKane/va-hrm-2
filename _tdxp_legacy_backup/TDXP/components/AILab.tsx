import { useEffect, useRef } from 'react';
import { motion, useReducedMotion } from 'framer-motion';
import { aiCapabilities } from '@/data/aiLab';
import * as Lucide from 'lucide-react';

const iconMap: Record<string, Lucide.LucideIcon> = {
    Code2: Lucide.Code2,
    Bot: Lucide.Bot,
    Workflow: Lucide.Workflow,
    Network: Lucide.Network,
    Sparkles: Lucide.Sparkles,
    Blocks: Lucide.Blocks,
};

function NeuralBackground() {
    const canvasRef = useRef<HTMLCanvasElement>(null);
    const reduce = useReducedMotion();

    useEffect(() => {
        const canvas = canvasRef.current;
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        let raf = 0;
        let visible = true;
        const particles: { x: number; y: number; vx: number; vy: number }[] = [];
        const count = 48;

        const resize = () => {
            const parent = canvas.parentElement;
            if (!parent) return;
            canvas.width = parent.clientWidth;
            canvas.height = parent.clientHeight;
        };
        resize();
        window.addEventListener('resize', resize);

        for (let i = 0; i < count; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                vx: (Math.random() - 0.5) * 0.4,
                vy: (Math.random() - 0.5) * 0.4,
            });
        }

        const draw = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
            grad.addColorStop(0, 'rgba(154,0,54,0.15)');
            grad.addColorStop(0.5, 'rgba(225,29,72,0.1)');
            grad.addColorStop(1, 'rgba(15,23,42,0.2)');
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            particles.forEach((p) => {
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
                if (p.y < 0 || p.y > canvas.height) p.vy *= -1;
            });

            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const a = particles[i];
                    const b = particles[j];
                    const dx = a.x - b.x;
                    const dy = a.y - b.y;
                    const dist = Math.hypot(dx, dy);
                    if (dist < 120) {
                        ctx.strokeStyle = `rgba(225,29,72,${0.25 * (1 - dist / 120)})`;
                        ctx.beginPath();
                        ctx.moveTo(a.x, a.y);
                        ctx.lineTo(b.x, b.y);
                        ctx.stroke();
                    }
                }
            }
            particles.forEach((p) => {
                ctx.fillStyle = 'rgba(255,255,255,0.6)';
                ctx.beginPath();
                ctx.arc(p.x, p.y, 2, 0, Math.PI * 2);
                ctx.fill();
            });
            if (visible && !reduce) raf = requestAnimationFrame(draw);
            else raf = 0;
        };

        // Tạm dừng vòng lặp khi section ngoài viewport hoặc người dùng tắt chuyển động.
        const io = new IntersectionObserver(
            ([entry]) => {
                visible = entry.isIntersecting;
                if (visible && !reduce && raf === 0) raf = requestAnimationFrame(draw);
            },
            { threshold: 0 },
        );
        io.observe(canvas);

        // Vẽ một khung tĩnh; nếu cho phép chuyển động thì khởi động vòng lặp.
        draw();
        if (reduce) cancelAnimationFrame(raf);

        return () => {
            cancelAnimationFrame(raf);
            io.disconnect();
            window.removeEventListener('resize', resize);
        };
    }, [reduce]);

    return <canvas ref={canvasRef} className="absolute inset-0 h-full w-full" aria-hidden />;
}

export function AILab() {
    return (
        <section id="ai" className="relative scroll-mt-24 overflow-hidden py-20 md:py-28">
            <div className="absolute inset-0 bg-secondary">
                <NeuralBackground />
            </div>
            <motion.div
                className="pointer-events-none absolute inset-0 bg-gradient-to-t from-secondary via-transparent to-primary/20"
                animate={{ opacity: [0.6, 1, 0.6] }}
                transition={{ duration: 8, repeat: Infinity }}
            />
            <div className="relative mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-12 max-w-2xl">
                    <p className="text-sm font-bold tracking-[0.2em] text-accent">ĐỔI MỚI SÁNG TẠO</p>
                    <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">AI &amp; Đổi mới sáng tạo</h2>
                    <p className="mt-3 text-white/60">
                        Nghiên cứu &amp; phát triển nội bộ — từ trợ lý lập trình đến tri thức đồ thị doanh nghiệp.
                    </p>
                </div>
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {aiCapabilities.map((cap, i) => {
                        const Icon = iconMap[cap.icon] ?? Lucide.Sparkles;
                        return (
                            <motion.div
                                key={cap.id}
                                className="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-md"
                                initial={{ opacity: 0, y: 16 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                viewport={{ once: true }}
                                transition={{ delay: i * 0.08 }}
                                whileHover={{ scale: 1.03, borderColor: 'rgba(225,29,72,0.5)' }}
                            >
                                <Icon className="h-8 w-8 text-accent" />
                                <h3 className="mt-4 text-lg font-semibold text-white">{cap.title}</h3>
                                <p className="mt-2 text-sm text-white/60">{cap.description}</p>
                            </motion.div>
                        );
                    })}
                </div>
            </div>
        </section>
    );
}
