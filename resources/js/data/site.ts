import type { SiteContent } from '@/types/tdxp';

/**
 * Thương hiệu + liên hệ toàn site (Hero copy & Footer).
 * → CMS sau này: bảng `site_settings` (singleton).
 */
export const site: SiteContent = {
    hero: {
        eyebrow: 'PHÒNG CÔNG NGHỆ',
        title: 'Kiến tạo nền tảng số cho giáo dục tương lai',
        subtitle: [
            'Xây dựng nền tảng số.',
            'Tự động hoá vận hành.',
            'Ứng dụng AI vào thực tiễn.',
        ],
        // 3 CTA theo yêu cầu spec — target là anchor id của section tương ứng.
        ctas: [
            { label: 'Khám phá đội ngũ', target: 'team', variant: 'primary' },
            { label: 'Dự án công nghệ', target: 'projects', variant: 'outline' },
            { label: 'Sơ đồ tổ chức', target: 'org', variant: 'outline' },
        ],
    },
    contact: {
        org: 'Phòng Công Nghệ · Hệ thống Trường Việt Mỹ VA School',
        email: 'phongcongnghe@vaschools.edu.vn',
        // TODO: cần Phòng xác nhận số điện thoại & địa chỉ trước khi công bố.
        phone: undefined,
        address: undefined,
    },
    socials: [
        // Chỉ để các kênh chắc chắn đúng. TODO: bổ sung Facebook/YouTube/LinkedIn nếu Phòng có.
        { id: 'website', label: 'vaschools.edu.vn', href: 'https://vaschools.edu.vn', icon: 'Globe' },
        { id: 'email', label: 'phongcongnghe@vaschools.edu.vn', href: 'mailto:phongcongnghe@vaschools.edu.vn', icon: 'Mail' },
    ],
};
