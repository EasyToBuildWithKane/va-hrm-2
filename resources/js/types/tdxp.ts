export interface Skill {
    name: string;
    level?: 'expert' | 'advanced' | 'intermediate';
}

export interface MemberProject {
    id: string;
    name: string;
    role: string;
    progress: number;
}

export interface TimelineItem {
    title: string;
    period: string;
    description?: string;
}

export interface WorkloadItem {
    label: string;
    value: number;
}

export interface SocialLinks {
    linkedin?: string;
    github?: string;
    email?: string;
}

/** Nhánh đội ngũ — dùng cho tab lọc Team. */
export type TeamBranch = 'software' | 'hardware';

export interface TeamMember {
    id: string;
    avatar: string;
    coverImage: string;
    name: string;
    role: string;
    department: string;
    /** Nhánh đội ngũ (Phần Mềm / Phần Cứng) — undefined cho cấp lãnh đạo phòng. */
    team?: TeamBranch;
    experience: string;
    quote: string;
    motto: string;
    bio: string;
    skills: Skill[];
    projects: MemberProject[];
    achievements: string[];
    gallery: string[];
    careerTimeline: TimelineItem[];
    workload: WorkloadItem[];
    socialLinks: SocialLinks;
    activeProjectCount: number;
    expertiseTags: string[];
}

/** Nhóm dự án theo vòng đời. */
export type ProjectGroup = 'rnd' | 'delivery' | 'operations';

export interface Project {
    id: string;
    name: string;
    client: string;
    description: string;
    results: string;
    technologies: string[];
    teamMemberIds: string[];
    progress: number;
    featured?: boolean;
    status: 'active' | 'completed' | 'planning';
    /** Nhóm vòng đời: Nghiên cứu & Phát triển · Triển khai & Nghiệm thu · Vận hành & Cải tiến. */
    group: ProjectGroup;
    timeline?: string;
    /** Chỉ dùng cho nhóm Vận hành. */
    uptime?: number;
    kpi?: string;
}

export type TechCategory =
    | 'language'
    | 'frontend'
    | 'backend'
    | 'database'
    | 'devops'
    | 'cloud'
    | 'ai';

export interface Technology {
    id: string;
    name: string;
    category: TechCategory;
    /** Logo file id under /assets/tech-logos/<logo>.svg */
    logo: string;
    description: string;
    /** True for technologies actually detected in this repo (composer/package). */
    inUse?: boolean;
}

export interface OrgNodeData {
    id: string;
    label: string;
    subtitle?: string;
    headcount?: number;
    children?: string[];
}

export interface MetricItem {
    id: string;
    value: number;
    suffix?: string;
    prefix?: string;
    label: string;
    decimals?: number;
}

export interface RoadmapItem {
    year: string;
    title: string;
    description: string;
}

export interface AICapability {
    id: string;
    title: string;
    description: string;
    icon: string;
}

// =============================================================================
// Landing-page content types (CMS-ready)
// Các shape dưới đây thiết kế để sau này map 1–1 sang bảng DB / endpoint API khi
// triển khai CMS. Hiện đọc tĩnh từ `resources/js/data/*.ts` (CMS tạm hoãn).
// =============================================================================

/** Liên kết mạng xã hội / kênh liên hệ (Footer). → map: bảng `site_socials`. */
export interface SocialLink {
    id: string;
    label: string;
    href: string;
    /** Tên icon lucide-react; Footer tra cứu động + fallback nên tên lạ không vỡ build. */
    icon: string;
}

/** Thương hiệu + liên hệ toàn site (Hero copy, Footer). → map: bảng `site_settings` (singleton). */
export interface SiteContent {
    hero: {
        eyebrow: string;
        title: string;
        subtitle: string[];
        ctas: { label: string; target: string; variant?: 'primary' | 'secondary' | 'outline' }[];
    };
    contact: {
        org: string;
        email: string;
        /** TODO: cần Phòng xác nhận. */
        phone?: string;
        /** TODO: cần Phòng xác nhận. */
        address?: string;
    };
    socials: SocialLink[];
}

/** Một giá trị cốt lõi. */
export interface ValueItem {
    id: string;
    title: string;
    description: string;
    icon: string;
}

/** Nội dung "Giới thiệu · Sứ mệnh – Tầm nhìn – Giá trị". → map: bảng `about_content` (singleton). */
export interface AboutContent {
    eyebrow: string;
    heading: string;
    intro: string;
    mission: { title: string; description: string };
    vision: { title: string; description: string };
    values: ValueItem[];
    /** Trụ minh hoạ cột trái (Data Center / AI / Cloud / EdTech). */
    pillars: { id: string; label: string; caption: string; icon: string }[];
}

/** Trạng thái sản phẩm trong Bento. */
export type ProductStatus = 'live' | 'beta' | 'development' | 'planned';

/** Sản phẩm trong "Hệ sinh thái sản phẩm" (Bento Grid). → map: bảng `products`. */
export interface Product {
    id: string;
    name: string;
    tagline: string;
    description: string;
    /** Icon lucide-react. */
    icon: string;
    /** Ảnh minh hoạ (placeholder coverUrl tới khi có ảnh thật). */
    image?: string;
    /** Link video demo — chưa có asset thật → để trống thì ẩn nút "Xem demo". */
    video?: string;
    href?: string;
    status: ProductStatus;
    technologies: string[];
    /** Kích thước ô bento: 'lg' nổi bật (2×2), 'md' (2×1), mặc định 'sm'. */
    size?: 'sm' | 'md' | 'lg';
    /** true = sản phẩm có thật; false = placeholder/định hướng, cần Phòng xác nhận. */
    confirmed?: boolean;
}

/** Một mục gallery Văn hoá (Masonry). → map: bảng `culture_items`. */
export interface CultureItem {
    id: string;
    type: 'image' | 'video';
    src: string;
    caption?: string;
    /** Gợi ý tỉ lệ cho masonry: 'tall' | 'wide' | 'square'. */
    aspect?: 'tall' | 'wide' | 'square';
}
