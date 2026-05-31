import type { TeamMember } from '@/types/tdxp';
import { coverUrl, galleryUrl } from '@/lib/utils';
import { mascotForSeed } from '@/lib/mascots';

function member(
    id: string,
    name: string,
    role: string,
    department: string,
    expertiseTags: string[],
    overrides: Partial<TeamMember> = {},
): TeamMember {
    const seed = id;
    return {
        id,
        name,
        role,
        department,
        experience: overrides.experience ?? '3+ năm',
        quote: overrides.quote ?? 'Không chỉ viết phần mềm. Chúng ta đang xây dựng nền tảng cho tương lai.',
        motto: overrides.motto ?? 'Đơn giản hóa điều phức tạp.',
        bio: overrides.bio ?? `${name} là thành viên ${department}, tập trung vào chất lượng sản phẩm và delivery bền vững.`,
        avatar: overrides.avatar ?? mascotForSeed(seed),
        coverImage: overrides.coverImage ?? coverUrl(seed),
        skills: overrides.skills ?? expertiseTags.map((label) => ({ name: label, level: 'advanced' as const })),
        projects: overrides.projects ?? [],
        achievements: overrides.achievements ?? [
            'Đóng góp ổn định vào các hệ thống nội bộ',
            'Phối hợp tốt giữa các nhóm chức năng',
        ],
        gallery: overrides.gallery ?? [0, 1, 2, 3, 4].map((i) => galleryUrl(seed, i)),
        careerTimeline: overrides.careerTimeline ?? [
            { title: 'Gia nhập Phòng Công Nghệ', period: '2022' },
            { title: role, period: '2024–nay' },
        ],
        workload: overrides.workload ?? [
            { label: 'Delivery', value: 75 },
            { label: 'Phối hợp', value: 70 },
            { label: 'Chất lượng', value: 80 },
            { label: 'Đổi mới', value: 55 },
        ],
        socialLinks: overrides.socialLinks ?? { email: `${id}@vaschools.edu.vn`, github: '#', linkedin: '#' },
        activeProjectCount: overrides.activeProjectCount ?? 2,
        expertiseTags,
        ...overrides,
    };
}

/**
 * Đội ngũ Phòng Công Nghệ.
 * - Cấp lãnh đạo phòng (không gắn `team`) chỉ phục vụ sơ đồ tổ chức.
 * - Thành viên hiển thị ở Team Section được gắn `team`: 'software' | 'hardware'.
 */
export const teamMembers: TeamMember[] = [
    // ---- Lãnh đạo phòng (org chart) ----
    member('toan', 'Bùi Quang Toàn', 'Giám đốc Công nghệ kiêm Trưởng phòng Công nghệ', 'Ban Công nghệ', ['Chiến lược', 'Lãnh đạo', 'Đổi mới sáng tạo'], {
        experience: '15+ năm',
        quote: 'Công nghệ chỉ thực sự có giá trị khi dẫn dắt được tăng trưởng.',
        motto: 'Tầm nhìn xa, hành động gần.',
    }),
    member('hoang', 'Bùi Huy Hoàng', 'Trợ lý Giám đốc Công nghệ kiêm Phó phòng Công nghệ', 'Ban Công nghệ', ['Quản trị', 'Vận hành', 'Điều phối'], {
        experience: '10+ năm',
        quote: 'Một đội ngũ mạnh là đội ngũ phối hợp nhịp nhàng.',
    }),
    member('hung', 'Nguyễn Viết Hùng', 'Trưởng Ban CNTT', 'Ban Công nghệ', ['Hạ tầng', 'An ninh hệ thống', 'Quản trị CNTT'], {
        experience: '10+ năm',
        quote: 'Hạ tầng vững vàng là nền móng cho mọi đổi mới.',
    }),

    // ---- Nhánh System (ngang cấp Leader nhưng không phải Leader) ----
    member('truong', 'Nguyễn Xuân Trường', 'System', 'Ban Công nghệ', ['System', 'DevOps', 'Hạ tầng'], {
        experience: '5+ năm',
        quote: 'Hệ thống chạy ổn định là thành công thầm lặng nhất.',
    }),

    // ---- Team Phần Mềm ----
    member('khoa', 'Nguyễn Anh Khoa', 'Team Leader Phần Mềm', 'Phần Mềm', ['Laravel', 'React', 'Architecture', 'AI'], {
        team: 'software',
        experience: '10+ năm',
        quote: 'Luôn tìm cách đơn giản hóa những vấn đề phức tạp.',
        activeProjectCount: 4,
        workload: [
            { label: 'Architecture', value: 88 },
            { label: 'Coding', value: 72 },
            { label: 'AI Research', value: 60 },
            { label: 'Mentoring', value: 65 },
        ],
    }),
    member('kieu', 'Nguyễn Lê Thanh Kiều', 'Business Analyst', 'Phần Mềm', ['Business Analysis', 'Phần mềm phòng ban', 'Requirement'], {
        team: 'software',
        bio: 'Phụ trách phân tích nghiệp vụ nhánh Phần mềm phòng ban — cầu nối giữa người dùng và đội phát triển.',
    }),
    member('hoa', 'Đinh Thị Thu Hoa', 'Business Analyst', 'Phần Mềm', ['Business Analysis', 'Phần mềm phòng ban', 'Documentation'], {
        team: 'software',
        bio: 'Phụ trách phân tích nghiệp vụ nhánh Phần mềm phòng ban — chuẩn hóa quy trình và tài liệu hóa yêu cầu.',
    }),
    member('truc', 'Hồ Thị Minh Trúc', 'Business Analyst', 'Phần Mềm', ['Business Analysis', 'Hội đồng Phổ thông', 'Hội đồng Tiếng Anh'], {
        team: 'software',
        bio: 'Phụ trách phân tích nghiệp vụ cho Hội đồng Phổ thông và Hội đồng Tiếng Anh.',
    }),
    member('binh', 'Trần Lê Bình', 'Developer', 'Phần Mềm', ['Laravel', 'Vue', 'MySQL'], {
        team: 'software',
    }),
    member('quang', 'Trần Minh Quang', 'Developer', 'Phần Mềm', ['React', 'TypeScript', 'Node.js'], {
        team: 'software',
    }),

    // ---- Team Phần Cứng ----
    member('thai', 'Phạm Quang Thái', 'Leader Phần Cứng', 'Phần Cứng', ['Network', 'Hardware', 'Infrastructure'], {
        team: 'hardware',
        experience: '8+ năm',
        quote: 'Phần cứng ổn định là điều kiện tiên quyết của mọi phần mềm tốt.',
        activeProjectCount: 3,
        workload: [
            { label: 'Network', value: 85 },
            { label: 'Hardware', value: 80 },
            { label: 'Support', value: 70 },
            { label: 'Mentoring', value: 55 },
        ],
    }),
    member('loc', 'Cam Đức Lộc', 'IT Support', 'Phần Cứng', ['IT Support', 'Hardware', 'Helpdesk'], {
        team: 'hardware',
    }),
    member('thang', 'Kha Cẩm Thắng', 'IT Support', 'Phần Cứng', ['IT Support', 'Network', 'Maintenance'], {
        team: 'hardware',
    }),
    member('vu', 'Nguyễn Hoàng Vũ', 'IT Support', 'Phần Cứng', ['IT Support', 'Setup', 'Troubleshooting'], {
        team: 'hardware',
    }),
];

export function getMemberById(id: string): TeamMember | undefined {
    return teamMembers.find((m) => m.id === id);
}
