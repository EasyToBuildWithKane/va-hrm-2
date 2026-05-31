import type { AICapability } from '@/types/tdxp';

export const aiCapabilities: AICapability[] = [
    {
        id: 'coding',
        title: 'AI Coding',
        description: 'Pair programming, codegen và review tự động trong pipeline phát triển.',
        icon: 'Code2',
    },
    {
        id: 'assistant',
        title: 'AI Assistant',
        description: 'Trợ lý nội bộ cho HR, quản lý và tra cứu chính sách.',
        icon: 'Bot',
    },
    {
        id: 'automation',
        title: 'Automation',
        description: 'RPA & workflow thông minh giảm thao tác thủ công.',
        icon: 'Workflow',
    },
    {
        id: 'knowledge',
        title: 'Knowledge Graph',
        description: 'Kết nối dữ liệu nhân sự, dự án và quy trình.',
        icon: 'Network',
    },
    {
        id: 'gpt',
        title: 'Internal GPT',
        description: 'Mô hình fine-tune trên tri thức VA Schools.',
        icon: 'Sparkles',
    },
    {
        id: 'lowcode',
        title: 'Low Code',
        description: 'Form & workflow builder cho nghiệp vụ nhanh.',
        icon: 'Blocks',
    },
];
