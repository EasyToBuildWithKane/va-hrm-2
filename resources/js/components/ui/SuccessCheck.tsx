import { motion, useReducedMotion } from 'framer-motion';

/** Dấu tick vẽ bằng SVG path-draw. */
export function SuccessCheck({ className }: { className?: string }) {
    const reduce = useReducedMotion();
    return (
        <svg viewBox="0 0 24 24" fill="none" className={className} aria-hidden>
            <motion.circle
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                strokeWidth="2"
                initial={reduce ? false : { pathLength: 0, opacity: 0 }}
                animate={{ pathLength: 1, opacity: 1 }}
                transition={{ duration: 0.4, ease: 'easeOut' }}
            />
            <motion.path
                d="M7 12.5l3.5 3.5L17 9"
                stroke="currentColor"
                strokeWidth="2.2"
                strokeLinecap="round"
                strokeLinejoin="round"
                initial={reduce ? false : { pathLength: 0 }}
                animate={{ pathLength: 1 }}
                transition={{ duration: 0.3, delay: 0.25, ease: 'easeOut' }}
            />
        </svg>
    );
}
