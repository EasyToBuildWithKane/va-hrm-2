import { useEffect, useRef } from 'react';
import { useReducedMotion } from 'framer-motion';

interface ParticleFieldProps {
    className?: string;
    /** Mật độ điểm trên mỗi 10.000 px² (mặc định ~0.9 — vừa phải, không nặng). */
    density?: number;
    /** Khoảng cách tối đa để nối 2 điểm bằng đường (px). */
    linkDistance?: number;
    /** Tốc độ trôi tối đa của điểm. */
    speed?: number;
    /** Màu đường nối (rgb, không gồm alpha). */
    lineRgb?: string;
    /** Màu điểm sáng (rgb, không gồm alpha). */
    dotRgb?: string;
    /** Vẽ "nút mạch" (ô vuông nhỏ) ở một phần điểm → cảm giác AI Circuit. */
    circuit?: boolean;
}

interface P {
    x: number;
    y: number;
    vx: number;
    vy: number;
    node: boolean;
}

/**
 * Nền canvas "Particle Network / AI Circuit" tái sử dụng (Hero, các section tối).
 * Phỏng theo vòng lặp canvas của AILab: DPR-aware, tạm dừng khi ngoài viewport
 * (IntersectionObserver) và tắt khi `prefers-reduced-motion` (vẽ 1 khung tĩnh).
 */
export function ParticleField({
    className,
    density = 0.9,
    linkDistance = 130,
    speed = 0.35,
    lineRgb = '255,92,138',
    dotRgb = '255,255,255',
    circuit = true,
}: ParticleFieldProps) {
    const canvasRef = useRef<HTMLCanvasElement>(null);
    const reduce = useReducedMotion();

    useEffect(() => {
        const canvas = canvasRef.current;
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        let raf = 0;
        let visible = true;
        let w = 0;
        let h = 0;
        let particles: P[] = [];

        const seed = () => {
            const target = Math.max(14, Math.round(((w * h) / 10000) * density));
            particles = Array.from({ length: target }, () => ({
                x: Math.random() * w,
                y: Math.random() * h,
                vx: (Math.random() - 0.5) * speed,
                vy: (Math.random() - 0.5) * speed,
                node: circuit && Math.random() < 0.22,
            }));
        };

        const resize = () => {
            const parent = canvas.parentElement;
            if (!parent) return;
            w = parent.clientWidth;
            h = parent.clientHeight;
            canvas.width = Math.max(1, Math.floor(w * dpr));
            canvas.height = Math.max(1, Math.floor(h * dpr));
            canvas.style.width = `${w}px`;
            canvas.style.height = `${h}px`;
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            seed();
        };

        const draw = () => {
            ctx.clearRect(0, 0, w, h);

            for (const p of particles) {
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0 || p.x > w) p.vx *= -1;
                if (p.y < 0 || p.y > h) p.vy *= -1;
            }

            // Đường nối — mờ dần theo khoảng cách.
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const a = particles[i];
                    const b = particles[j];
                    const dx = a.x - b.x;
                    const dy = a.y - b.y;
                    const dist = Math.hypot(dx, dy);
                    if (dist < linkDistance) {
                        ctx.strokeStyle = `rgba(${lineRgb},${0.22 * (1 - dist / linkDistance)})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(a.x, a.y);
                        ctx.lineTo(b.x, b.y);
                        ctx.stroke();
                    }
                }
            }

            // Điểm sáng + nút mạch (circuit).
            for (const p of particles) {
                if (p.node) {
                    ctx.fillStyle = `rgba(${lineRgb},0.9)`;
                    ctx.fillRect(p.x - 2, p.y - 2, 4, 4);
                } else {
                    ctx.fillStyle = `rgba(${dotRgb},0.55)`;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, 1.6, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            if (visible && !reduce) raf = requestAnimationFrame(draw);
            else raf = 0;
        };

        resize();
        window.addEventListener('resize', resize);

        const io = new IntersectionObserver(
            ([entry]) => {
                visible = entry.isIntersecting;
                if (visible && !reduce && raf === 0) raf = requestAnimationFrame(draw);
            },
            { threshold: 0 },
        );
        io.observe(canvas);

        draw(); // luôn vẽ 1 khung tĩnh
        if (reduce) cancelAnimationFrame(raf);

        return () => {
            cancelAnimationFrame(raf);
            io.disconnect();
            window.removeEventListener('resize', resize);
        };
    }, [reduce, density, linkDistance, speed, lineRgb, dotRgb, circuit]);

    return <canvas ref={canvasRef} className={className} aria-hidden />;
}
