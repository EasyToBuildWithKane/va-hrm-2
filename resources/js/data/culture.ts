import type { CultureItem } from '@/types/tdxp';
import { galleryUrl } from '@/lib/utils';

/**
 * Văn hoá Phòng Công Nghệ — Masonry Gallery.
 * → CMS sau này: bảng `culture_items`.
 *
 * LƯU Ý: ẢNH HIỆN LÀ PLACEHOLDER (picsum qua galleryUrl) — caption mô tả hoạt động dự kiến.
 * TODO: thay bằng ảnh/video hoạt động THẬT của Phòng (workshop, hackathon, team building…).
 */
export const cultureItems: CultureItem[] = [
    { id: 'c1', type: 'image', src: galleryUrl('culture', 1), caption: 'Daily standup & review', aspect: 'wide' },
    { id: 'c2', type: 'image', src: galleryUrl('culture', 2), caption: 'Hackathon nội bộ', aspect: 'tall' },
    { id: 'c3', type: 'image', src: galleryUrl('culture', 3), caption: 'Workshop công nghệ', aspect: 'square' },
    { id: 'c4', type: 'image', src: galleryUrl('culture', 4), caption: 'Pair programming', aspect: 'square' },
    { id: 'c5', type: 'image', src: galleryUrl('culture', 5), caption: 'Team building', aspect: 'wide' },
    { id: 'c6', type: 'image', src: galleryUrl('culture', 6), caption: 'Demo day sản phẩm', aspect: 'tall' },
    { id: 'c7', type: 'image', src: galleryUrl('culture', 7), caption: 'Chia sẻ kiến thức AI', aspect: 'square' },
    { id: 'c8', type: 'image', src: galleryUrl('culture', 8), caption: 'Triển khai tại cơ sở', aspect: 'wide' },
    { id: 'c9', type: 'image', src: galleryUrl('culture', 9), caption: 'Vận hành & trực hệ thống', aspect: 'square' },
];
