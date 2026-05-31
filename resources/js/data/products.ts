import type { Product } from '@/types/tdxp';
import { coverUrl } from '@/lib/utils';

/**
 * Hệ sinh thái sản phẩm (Bento Grid).
 * → CMS sau này: bảng `products`.
 *
 * Nguyên tắc nội dung: CHỈ DỮ LIỆU THẬT.
 * - `confirmed: true`  → sản phẩm có thật (đối chiếu projects.ts).
 * - `confirmed: false` → định hướng/placeholder (CRM, LMS, Mobile App) — gắn nhãn "Sắp ra mắt",
 *   cần Phòng xác nhận trước khi công bố là sản phẩm thật.
 * - `image` dùng placeholder `coverUrl()` (picsum) cho tới khi có ảnh thật.
 * - `video` để trống vì chưa có video demo thật → UI tự ẩn nút "Xem demo".
 */
export const products: Product[] = [
    {
        id: 'hrm',
        name: 'HRM Platform',
        tagline: 'Quản trị nhân sự modular, API-first',
        description:
            'Nền tảng quản trị nhân sự cho toàn hệ thống: hồ sơ, phòng ban, chấm công, nghỉ phép và workflow phê duyệt.',
        icon: 'UsersRound',
        image: coverUrl('product-hrm'),
        status: 'development',
        technologies: ['Laravel', 'React', 'MySQL', 'Redis'],
        size: 'lg',
        confirmed: true,
    },
    {
        id: 'ai-assistant',
        name: 'AI Assistant Nội Bộ',
        tagline: 'Trợ lý AI tra cứu chính sách & nghiệp vụ',
        description:
            'Trợ lý hỏi–đáp trên dữ liệu nội bộ (RAG): tra cứu chính sách, quy trình và hỗ trợ nghiệp vụ.',
        icon: 'Bot',
        image: coverUrl('product-ai'),
        status: 'beta',
        technologies: ['Claude', 'OpenAI', 'LangChain', 'Python'],
        size: 'md',
        confirmed: true,
    },
    {
        id: 'workflow',
        name: 'Automation Workflow',
        tagline: '120+ luồng tự động hoá đang chạy',
        description:
            'Tự động hoá tác vụ vận hành lặp lại: tạo tài khoản, đồng bộ dữ liệu, cấp phát email/license và báo cáo định kỳ.',
        icon: 'Workflow',
        image: coverUrl('product-workflow'),
        status: 'live',
        technologies: ['Laravel', 'Python', 'Automation'],
        size: 'md',
        confirmed: true,
    },
    {
        id: 'portal',
        name: 'Employee Portal',
        tagline: 'Cổng self-service cho nhân viên',
        description: 'Đơn từ, bảng lương, thông báo và tra cứu thông tin cá nhân — một cửa cho nhân viên.',
        icon: 'LayoutDashboard',
        image: coverUrl('product-portal'),
        status: 'beta',
        technologies: ['React', 'TypeScript', 'Laravel'],
        size: 'sm',
        confirmed: true,
    },
    {
        id: 'attendance',
        name: 'Smart Attendance',
        tagline: 'Chấm công & đồng bộ payroll realtime',
        description: 'Chấm công, ca làm việc và đồng bộ realtime với HRM, đang vận hành ổn định.',
        icon: 'Fingerprint',
        image: coverUrl('product-attendance'),
        status: 'live',
        technologies: ['Laravel', 'MySQL', 'Redis'],
        size: 'sm',
        confirmed: true,
    },
    // ---- Định hướng / placeholder (spec gợi ý) — chưa có thật, cần xác nhận ----
    {
        id: 'lms',
        name: 'LMS',
        tagline: 'Hệ thống quản lý học tập',
        description: 'Định hướng: nền tảng học liệu & quản lý lớp học. Chưa triển khai — cần Phòng xác nhận lộ trình.',
        icon: 'BookOpen',
        image: coverUrl('product-lms'),
        status: 'planned',
        technologies: ['EdTech'],
        size: 'sm',
        confirmed: false,
    },
    {
        id: 'crm',
        name: 'CRM',
        tagline: 'Quản lý quan hệ tuyển sinh',
        description: 'Định hướng: quản lý quan hệ phụ huynh & tuyển sinh. Chưa triển khai — cần Phòng xác nhận lộ trình.',
        icon: 'HeartHandshake',
        image: coverUrl('product-crm'),
        status: 'planned',
        technologies: ['CRM'],
        size: 'sm',
        confirmed: false,
    },
    {
        id: 'mobile',
        name: 'Mobile App',
        tagline: 'Ứng dụng di động đa nền tảng',
        description: 'Định hướng: app di động cho nhân viên & phụ huynh. Chưa triển khai — cần Phòng xác nhận lộ trình.',
        icon: 'Smartphone',
        image: coverUrl('product-mobile'),
        status: 'planned',
        technologies: ['Mobile'],
        size: 'sm',
        confirmed: false,
    },
];
