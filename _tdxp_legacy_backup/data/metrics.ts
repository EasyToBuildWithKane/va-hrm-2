import type { MetricItem } from '@/types/tdxp';

export const impactMetrics: MetricItem[] = [
    { id: 'staff', value: 14, suffix: '', label: 'Nhân sự' },
    { id: 'projects', value: 13, suffix: '+', label: 'Dự án' },
    { id: 'systems', value: 12, suffix: '+', label: 'Hệ thống' },
    { id: 'automation', value: 120, suffix: '+', label: 'Luồng tự động hóa' },
    { id: 'delivery', value: 85, suffix: '%', label: 'On-Time Delivery' },
    { id: 'efficiency', value: 40, suffix: '%', label: 'Tăng hiệu suất vận hành' },
];
