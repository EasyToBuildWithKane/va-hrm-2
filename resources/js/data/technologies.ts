import type { Technology } from '@/types/tdxp';

/**
 * Catalog công nghệ. `logo` trỏ tới /assets/tech-logos/<logo>.svg (SVG đơn sắc — simple-icons).
 * `inUse: true` = công nghệ phát hiện trực tiếp trong composer.json / package.json của repo này.
 */
export const technologies: Technology[] = [
    // Ngôn ngữ
    { id: 'php', name: 'PHP', category: 'language', logo: 'php', description: 'Nền tảng backend & hệ sinh thái Laravel', inUse: true },
    { id: 'typescript', name: 'TypeScript', category: 'language', logo: 'typescript', description: 'Frontend an toàn kiểu & hợp đồng dùng chung', inUse: true },
    { id: 'javascript', name: 'JavaScript', category: 'language', logo: 'javascript', description: 'Scripting full-stack & công cụ build', inUse: true },
    { id: 'python', name: 'Python', category: 'language', logo: 'python', description: 'Pipeline AI & script tự động hóa' },
    { id: 'java', name: 'Java', category: 'language', logo: 'java', description: 'Tích hợp hệ thống doanh nghiệp' },
    { id: 'csharp', name: 'C#', category: 'language', logo: 'csharp', description: 'Ứng dụng & dịch vụ .NET' },
    { id: 'go', name: 'Go', category: 'language', logo: 'go', description: 'Microservice hiệu năng cao' },
    { id: 'rust', name: 'Rust', category: 'language', logo: 'rust', description: 'Hiệu năng & an toàn bộ nhớ' },

    // Frontend
    { id: 'react', name: 'React', category: 'frontend', logo: 'react', description: 'Lớp trải nghiệm & dashboard', inUse: true },
    { id: 'nextjs', name: 'Next.js', category: 'frontend', logo: 'nextjs', description: 'SSR cho portal nội bộ & marketing' },
    { id: 'vue', name: 'Vue', category: 'frontend', logo: 'vue', description: 'UI cho các module kế thừa' },
    { id: 'nuxt', name: 'Nuxt', category: 'frontend', logo: 'nuxt', description: 'SSR cho ứng dụng Vue' },
    { id: 'tailwind', name: 'Tailwind CSS', category: 'frontend', logo: 'tailwind', description: 'Tốc độ dựng design system', inUse: true },
    { id: 'framer', name: 'Framer Motion', category: 'frontend', logo: 'framer', description: 'Chuyển động UX cao cấp', inUse: true },
    { id: 'vite', name: 'Vite', category: 'frontend', logo: 'vite', description: 'Pipeline build frontend', inUse: true },

    // Backend
    { id: 'laravel', name: 'Laravel', category: 'backend', logo: 'laravel', description: 'Nền tảng HRM & API dạng modular', inUse: true },
    { id: 'inertia', name: 'Inertia.js', category: 'backend', logo: 'inertia', description: 'Cầu nối SPA không cần REST', inUse: true },
    { id: 'nestjs', name: 'NestJS', category: 'backend', logo: 'nestjs', description: 'Dịch vụ Node có cấu trúc' },
    { id: 'spring', name: 'Spring Boot', category: 'backend', logo: 'spring', description: 'Backend doanh nghiệp Java' },
    { id: 'fastapi', name: 'FastAPI', category: 'backend', logo: 'fastapi', description: 'API phục vụ mô hình AI' },

    // Cơ sở dữ liệu
    { id: 'mysql', name: 'MySQL', category: 'database', logo: 'mysql', description: 'Kho dữ liệu giao dịch chính', inUse: true },
    { id: 'postgresql', name: 'PostgreSQL', category: 'database', logo: 'postgresql', description: 'Phân tích & báo cáo' },
    { id: 'redis', name: 'Redis', category: 'database', logo: 'redis', description: 'Cache, hàng đợi, session', inUse: true },
    { id: 'mongodb', name: 'MongoDB', category: 'database', logo: 'mongodb', description: 'Dữ liệu phi cấu trúc' },
    { id: 'elasticsearch', name: 'Elasticsearch', category: 'database', logo: 'elasticsearch', description: 'Tìm kiếm & log tập trung' },

    // DevOps
    { id: 'docker', name: 'Docker', category: 'devops', logo: 'docker', description: 'Đóng gói & triển khai container' },
    { id: 'kubernetes', name: 'Kubernetes', category: 'devops', logo: 'kubernetes', description: 'Điều phối quy mô lớn' },
    { id: 'git', name: 'Git', category: 'devops', logo: 'git', description: 'Quản lý phiên bản', inUse: true },
    { id: 'github', name: 'GitHub', category: 'devops', logo: 'github', description: 'CI/CD & cộng tác mã nguồn' },
    { id: 'nginx', name: 'Nginx', category: 'devops', logo: 'nginx', description: 'Định tuyến biên & TLS' },
    { id: 'jenkins', name: 'Jenkins', category: 'devops', logo: 'jenkins', description: 'Tự động hóa CI/CD' },
    { id: 'terraform', name: 'Terraform', category: 'devops', logo: 'terraform', description: 'Hạ tầng dưới dạng mã' },

    // Cloud
    { id: 'aws', name: 'AWS', category: 'cloud', logo: 'aws', description: 'Hạ tầng đám mây' },
    { id: 'azure', name: 'Azure', category: 'cloud', logo: 'azure', description: 'Dịch vụ đám mây Microsoft' },
    { id: 'gcp', name: 'Google Cloud', category: 'cloud', logo: 'gcp', description: 'Hạ tầng & AI trên GCP' },

    // AI
    { id: 'openai', name: 'OpenAI', category: 'ai', logo: 'openai', description: 'Trợ lý LLM & sinh mã' },
    { id: 'claude', name: 'Claude', category: 'ai', logo: 'claude', description: 'Tác tử suy luận & review' },
    { id: 'gemini', name: 'Gemini', category: 'ai', logo: 'gemini', description: 'Thử nghiệm đa phương thức' },
    { id: 'ollama', name: 'Ollama', category: 'ai', logo: 'ollama', description: 'LLM chạy nội bộ on-premise' },
    { id: 'langchain', name: 'LangChain', category: 'ai', logo: 'langchain', description: 'Điều phối tác tử' },
];

export const orbitRings = {
    languages: ['PHP', 'TypeScript', 'JavaScript', 'Python', 'Java'],
    frameworks: ['Laravel', 'React', 'Next.js', 'Vue'],
    infrastructure: ['Docker', 'Redis', 'MySQL', 'PostgreSQL', 'AWS', 'Kubernetes'],
    ai: ['OpenAI', 'Claude', 'Gemini', 'LangChain'],
} as const;
