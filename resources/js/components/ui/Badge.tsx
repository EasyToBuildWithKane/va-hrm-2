import { cn } from '@/lib/utils';

interface BadgeProps {
    children: React.ReactNode;
    className?: string;
    variant?: 'default' | 'accent' | 'outline';
}

export function Badge({ children, className, variant = 'default' }: BadgeProps) {
    return (
        <span
            className={cn(
                'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium',
                variant === 'default' && 'bg-glow/15 text-glow',
                variant === 'accent' && 'bg-glow/15 text-glow',
                variant === 'outline' && 'border border-white/15 bg-white/10 text-white/85',
                className,
            )}
        >
            {children}
        </span>
    );
}
