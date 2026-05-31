import { Inbox } from 'lucide-react';
import type { LucideIcon } from 'lucide-react';

interface EmptyStateProps {
    icon?: LucideIcon;
    title?: string;
    description?: string;
    action?: React.ReactNode;
    className?: string;
}

export function EmptyState({
    icon: Icon = Inbox,
    title = 'Chưa có dữ liệu',
    description = 'Dữ liệu sẽ hiển thị ngay khi được cấu hình từ Admin.',
    action,
    className,
}: EmptyStateProps) {
    return (
        <div
            className={`flex flex-col items-center justify-center rounded-xl border border-dashed border-white/15 bg-white/5 px-6 py-10 text-center ${className ?? ''}`}
        >
            <span className="flex h-12 w-12 items-center justify-center rounded-full bg-glow/15 text-glow">
                <Icon className="h-6 w-6" />
            </span>
            <p className="mt-4 font-semibold text-white">{title}</p>
            <p className="mt-1 max-w-xs text-sm text-white/55">{description}</p>
            {action && <div className="mt-4">{action}</div>}
        </div>
    );
}
