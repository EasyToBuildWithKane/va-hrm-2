import * as Lucide from 'lucide-react';

const registry = Lucide as unknown as Record<string, Lucide.LucideIcon>;

/**
 * Tra cứu icon lucide-react theo TÊN (chuỗi) — phục vụ nội dung CMS/data file.
 * Fallback về `Sparkles` nếu tên không tồn tại → tên lạ không làm vỡ build/UI.
 */
export function getLucideIcon(name: string): Lucide.LucideIcon {
    return registry[name] ?? Lucide.Sparkles;
}
