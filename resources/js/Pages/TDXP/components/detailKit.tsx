import type { ReactNode } from 'react';
import { getLucideIcon } from '@/lib/lucide';

/**
 * Bộ primitive dùng chung cho các panel chi tiết (thành viên & dự án) → đảm bảo
 * giao diện đồng bộ: tiêu đề section, lưới chỉ số, chip thống kê.
 */

export function DetailSection({ title, icon, children }: { title: string; icon?: string; children: ReactNode }) {
    const Icon = icon ? getLucideIcon(icon) : null;
    return (
        <section>
            <h3 className="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-glow/80">
                {Icon && <Icon className="h-4 w-4" />}
                {title}
            </h3>
            <div className="mt-3">{children}</div>
        </section>
    );
}

export function StatGrid({ children }: { children: ReactNode }) {
    return <div className="grid grid-cols-3 gap-3">{children}</div>;
}

export function Stat({ label, value }: { label: string; value: ReactNode }) {
    return (
        <div className="rounded-xl border border-white/10 bg-white/5 p-3 text-center">
            <p className="text-lg font-bold tabular-nums text-white">{value}</p>
            <p className="mt-0.5 text-[11px] leading-tight text-white/55">{label}</p>
        </div>
    );
}
