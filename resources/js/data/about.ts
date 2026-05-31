import type { AboutContent } from '@/types/tdxp';

/**
 * Nội dung "Giới thiệu · Sứ mệnh – Tầm nhìn – Giá trị cốt lõi".
 * → CMS sau này: bảng `about_content` (singleton).
 *
 * LƯU Ý: copy dưới đây là bản SOẠN NHÁP (suy ra từ tagline/quote trong team.ts).
 * TODO: cần Ban lãnh đạo Phòng Công Nghệ rà soát & chốt nội dung chính thức.
 */
export const about: AboutContent = {
    eyebrow: 'GIỚI THIỆU',
    heading: 'Phòng Công Nghệ VA School',
    intro:
        'Phòng Công Nghệ là đơn vị kiến tạo và vận hành nền tảng số cho toàn hệ thống Trường Việt Mỹ — ' +
        'từ hạ tầng, phần mềm nghiệp vụ đến các sáng kiến AI, hướng tới một môi trường giáo dục hiện đại, ' +
        'minh bạch và hiệu quả.',
    mission: {
        title: 'Sứ mệnh',
        description:
            'Số hoá và tự động hoá vận hành nhà trường, đưa công nghệ phục vụ trực tiếp cho dạy – học – quản trị, ' +
            'để mỗi quy trình đều nhanh hơn, chính xác hơn và lấy con người làm trung tâm.',
    },
    vision: {
        title: 'Tầm nhìn',
        description:
            'Trở thành đơn vị công nghệ giáo dục tiên phong, vận hành trên dữ liệu và AI, ' +
            'làm nền tảng tăng trưởng bền vững cho hệ thống Trường Việt Mỹ.',
    },
    values: [
        { id: 'people', title: 'Lấy người dùng làm trung tâm', description: 'Giải pháp xuất phát từ nhu cầu thực tế của giáo viên, học sinh và cán bộ.', icon: 'Users' },
        { id: 'quality', title: 'Chất lượng & tin cậy', description: 'Hệ thống ổn định, an toàn dữ liệu, vận hành liên tục 24/7.', icon: 'ShieldCheck' },
        { id: 'innovation', title: 'Đổi mới sáng tạo', description: 'Liên tục thử nghiệm AI, tự động hoá và công nghệ mới.', icon: 'Lightbulb' },
        { id: 'collaboration', title: 'Phối hợp & minh bạch', description: 'Làm việc nhóm chặt chẽ, quy trình rõ ràng, đo lường bằng dữ liệu.', icon: 'HeartHandshake' },
    ],
    // Trụ minh hoạ cột trái — ghép từ icon (không cần ảnh mới).
    pillars: [
        { id: 'datacenter', label: 'Data Center', caption: 'Hạ tầng & máy chủ', icon: 'Server' },
        { id: 'ai', label: 'AI', caption: 'Trí tuệ nhân tạo', icon: 'BrainCircuit' },
        { id: 'cloud', label: 'Cloud', caption: 'Điện toán đám mây', icon: 'Cloud' },
        { id: 'edtech', label: 'EdTech', caption: 'Công nghệ giáo dục', icon: 'GraduationCap' },
    ],
};
