import { cn } from '@/lib/utils';

interface ProgressBarProps {
    label: string;
    value: number;
    className?: string;
    showLabel?: boolean;
}

export function ProgressBar({ label, value, className, showLabel = true }: ProgressBarProps) {
    const clamped = Math.min(100, Math.max(0, value));
    return (
        <div className={cn('space-y-1.5', className)}>
            {showLabel && (
                <div className="flex justify-between text-xs text-white/70">
                    <span>{label}</span>
                    <span>{clamped}%</span>
                </div>
            )}
            <div className="h-2 overflow-hidden rounded-full bg-white/15">
                <div
                    className="h-full rounded-full bg-gradient-to-r from-glow to-accent transition-all duration-700"
                    style={{ width: `${clamped}%` }}
                    role="progressbar"
                    aria-valuenow={clamped}
                    aria-valuemin={0}
                    aria-valuemax={100}
                    aria-label={label}
                />
            </div>
        </div>
    );
}
