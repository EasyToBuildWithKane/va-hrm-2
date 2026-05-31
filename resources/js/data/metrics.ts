import type { MetricItem } from '@/types/tdxp';

/**
 * Thành tựu nổi bật (Counter Animation).
 * → CMS sau này: bảng `metrics`.
 *
 * Nguyên tắc: CHỈ DỮ LIỆU THẬT (đối chiếu projects.ts / technologies.ts).
 * - Spec gợi ý "30+ hệ thống / 10+ dự án" → dùng số thật 12+/13+.
 * - Spec "5+ công nghệ AI" → ĐÚNG: có 5 (OpenAI, Claude, Gemini, Ollama, LangChain).
 * - "Uptime" gắn nhãn "hạ tầng" (99.8% theo projects.ts) — tránh tuyên bố tổng thể sai.
 * - "1000+ người dùng/ngày": CHƯA có số liệu đo → để placeholder bên dưới, KHÔNG hiển thị
 *   tới khi Phòng xác nhận (bỏ comment để bật lại).
 */
export const impactMetrics: MetricItem[] = [
    { id: 'staff', value: 14, suffix: '', label: 'Nhân sự' },
    { id: 'projects', value: 13, suffix: '+', label: 'Dự án' },
    { id: 'systems', value: 12, suffix: '+', label: 'Hệ thống vận hành' },
    { id: 'automation', value: 120, suffix: '+', label: 'Luồng tự động hoá' },
    { id: 'ai', value: 5, suffix: '+', label: 'Công nghệ AI' },
    { id: 'uptime', value: 99.8, suffix: '%', decimals: 1, label: 'Uptime hạ tầng' },

    // TODO (cần Phòng xác nhận số liệu thực đo trước khi công bố):
    // { id: 'users', value: 1000, suffix: '+', label: 'Người dùng/ngày' },
];
