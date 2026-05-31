import { cn } from '@/lib/utils';

/** Khối skeleton dùng shimmer (.skeleton định nghĩa trong app.css). */
export function Skeleton({ className }: { className?: string }) {
    return <div className={cn('skeleton rounded-md', className)} aria-hidden />;
}
