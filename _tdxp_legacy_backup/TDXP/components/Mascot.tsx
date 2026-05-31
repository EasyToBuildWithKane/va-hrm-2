import { motion, useReducedMotion } from 'framer-motion';
import { MASCOT, type MascotPose } from '@/lib/mascots';
import { cn } from '@/lib/utils';

interface MascotProps {
    pose?: MascotPose;
    /** Chiều cao px của ảnh mascot. */
    size?: number;
    /** Hiệu ứng nổi lên xuống. */
    float?: boolean;
    /** Quầng sáng đỏ brand phía sau. */
    glow?: boolean;
    className?: string;
    /** alt cho a11y; mặc định ẩn (decorative). */
    alt?: string;
    priority?: boolean;
}

/**
 * Mascot — linh vật Phòng Công Nghệ. Floating + glow tôn trọng prefers-reduced-motion.
 */
export function Mascot({
    pose = 'stand',
    size = 280,
    float = false,
    glow = false,
    className,
    alt = '',
    priority = false,
}: MascotProps) {
    const reduce = useReducedMotion();
    const animate = float && !reduce ? { y: [0, -16, 0] } : undefined;

    return (
        <motion.div
            className={cn('relative inline-flex items-end justify-center', className)}
            animate={animate}
            transition={animate ? { duration: 5, repeat: Infinity, ease: 'easeInOut' } : undefined}
        >
            {glow && (
                <span
                    aria-hidden
                    className="pointer-events-none absolute inset-0 -z-10 m-auto rounded-full blur-3xl"
                    style={{
                        width: size * 0.8,
                        height: size * 0.8,
                        background:
                            'radial-gradient(circle, rgba(195,0,74,0.45), rgba(154,0,54,0.18) 45%, transparent 70%)',
                    }}
                />
            )}
            <img
                src={MASCOT[pose]}
                alt={alt}
                aria-hidden={alt === '' ? true : undefined}
                height={size}
                style={{ height: size, width: 'auto' }}
                loading={priority ? 'eager' : 'lazy'}
                decoding="async"
                draggable={false}
                className="select-none drop-shadow-[0_24px_48px_rgba(16,42,67,0.25)]"
            />
        </motion.div>
    );
}
