import { useMemo } from 'react';
import { motion, useReducedMotion } from 'framer-motion';
import {
    ResponsiveContainer,
    PieChart,
    Pie,
    Cell,
    BarChart,
    Bar,
    XAxis,
    YAxis,
    Tooltip,
    RadialBarChart,
    RadialBar,
    PolarAngleAxis,
} from 'recharts';
import { Users, Rocket, Cpu, Sparkles, TrendingUp, Activity } from 'lucide-react';
import { teamMembers } from '@/data/team';
import { projects } from '@/data/projects';
import { technologies } from '@/data/technologies';
import { aiCapabilities } from '@/data/aiLab';
import { EmptyState } from './EmptyState';

const PALETTE = ['#9A0036', '#E11D48', '#102A43', '#2563EB', '#7C3AED', '#0891B2'];
const AI_TECHS = ['OpenAI', 'Claude', 'Gemini', 'LangChain', 'Ollama'];

function Card({
    title,
    icon: Icon,
    children,
    className,
}: {
    title: string;
    icon: React.ComponentType<{ className?: string }>;
    children: React.ReactNode;
    className?: string;
}) {
    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5 }}
            className={`flex flex-col rounded-2xl border border-secondary/10 bg-white p-6 shadow-[var(--shadow-soft)] ${className ?? ''}`}
        >
            <div className="mb-4 flex items-center gap-2 text-secondary/60">
                <Icon className="h-4 w-4 text-primary" />
                <h3 className="text-sm font-semibold uppercase tracking-wider">{title}</h3>
            </div>
            <div className="flex-1">{children}</div>
        </motion.div>
    );
}

const tooltipStyle = {
    borderRadius: 12,
    border: '1px solid rgba(16,42,67,0.1)',
    fontSize: 12,
    boxShadow: '0 8px 24px rgba(16,42,67,0.12)',
};

export default function Dashboard() {
    const reduce = useReducedMotion();
    const anim = !reduce;

    const stats = useMemo(() => {
        const byDept = Object.entries(
            teamMembers.reduce<Record<string, number>>((acc, m) => {
                acc[m.department] = (acc[m.department] ?? 0) + 1;
                return acc;
            }, {}),
        ).map(([name, value]) => ({ name, value }));

        const status = { active: 0, completed: 0, planning: 0 };
        projects.forEach((p) => (status[p.status] += 1));
        const statusData = [
            { name: 'Đang chạy', value: status.active },
            { name: 'Hoàn thành', value: status.completed },
            { name: 'Kế hoạch', value: status.planning },
        ];

        const avgProgress = Math.round(projects.reduce((s, p) => s + p.progress, 0) / projects.length);
        const topProjects = [...projects]
            .sort((a, b) => b.progress - a.progress)
            .slice(0, 6)
            .map((p) => ({ name: p.name, progress: p.progress }));

        const techByCat = Object.entries(
            technologies.reduce<Record<string, number>>((acc, t) => {
                acc[t.category] = (acc[t.category] ?? 0) + 1;
                return acc;
            }, {}),
        ).map(([name, value]) => ({ name, value }));
        const techInUse = technologies.filter((t) => t.inUse).length;

        const aiProjects = projects.filter((p) =>
            p.technologies.some((t) => AI_TECHS.includes(t)),
        ).length;

        return {
            byDept,
            statusData,
            activeCount: status.active,
            avgProgress,
            topProjects,
            techByCat,
            techInUse,
            aiProjects,
        };
    }, []);

    const catLabels: Record<string, string> = {
        language: 'Ngôn ngữ',
        frontend: 'Frontend',
        backend: 'Backend',
        database: 'Database',
        devops: 'DevOps',
        cloud: 'Cloud',
        ai: 'AI',
    };

    return (
        <section className="bg-surface py-20 md:py-28">
            <div className="mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-10 flex items-end justify-between gap-4">
                    <div className="max-w-2xl">
                        <p className="text-sm font-bold tracking-[0.2em] text-primary">TỔNG QUAN</p>
                        <h2 className="mt-3 text-3xl font-bold text-secondary md:text-4xl">Bảng điều hành</h2>
                        <p className="mt-3 text-secondary/60">
                            Bức tranh tổng thể nhân sự, dự án, công nghệ và đổi mới — cập nhật theo thời gian thực.
                        </p>
                    </div>
                    <span className="hidden shrink-0 items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 sm:flex">
                        <span className="relative flex h-2 w-2">
                            {anim && <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75" />}
                            <span className="relative inline-flex h-2 w-2 rounded-full bg-emerald-500" />
                        </span>
                        Trực tuyến
                    </span>
                </div>

                <div className="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    {/* Tổng nhân sự */}
                    <Card title="Tổng nhân sự" icon={Users}>
                        <div className="flex items-center gap-4">
                            <div>
                                <p className="text-4xl font-bold tabular-nums text-secondary">{teamMembers.length}</p>
                                <p className="text-sm text-secondary/55">thành viên</p>
                            </div>
                            <div className="h-28 flex-1">
                                <ResponsiveContainer width="100%" height="100%">
                                    <PieChart>
                                        <Pie data={stats.byDept} dataKey="value" nameKey="name" innerRadius={28} outerRadius={48} paddingAngle={2} isAnimationActive={anim}>
                                            {stats.byDept.map((_, i) => (
                                                <Cell key={i} fill={PALETTE[i % PALETTE.length]} />
                                            ))}
                                        </Pie>
                                        <Tooltip contentStyle={tooltipStyle} />
                                    </PieChart>
                                </ResponsiveContainer>
                            </div>
                        </div>
                        <ul className="mt-3 flex flex-wrap gap-x-4 gap-y-1">
                            {stats.byDept.map((d, i) => (
                                <li key={d.name} className="flex items-center gap-1.5 text-xs text-secondary/60">
                                    <span className="h-2 w-2 rounded-full" style={{ background: PALETTE[i % PALETTE.length] }} />
                                    {d.name} ({d.value})
                                </li>
                            ))}
                        </ul>
                    </Card>

                    {/* Dự án đang triển khai */}
                    <Card title="Dự án đang triển khai" icon={Rocket}>
                        <div className="flex items-baseline gap-2">
                            <p className="text-4xl font-bold tabular-nums text-secondary">{stats.activeCount}</p>
                            <p className="text-sm text-secondary/55">/ {projects.length} dự án</p>
                        </div>
                        <div className="mt-3 h-32">
                            <ResponsiveContainer width="100%" height="100%">
                                <BarChart data={stats.statusData} margin={{ top: 8, right: 8, left: -20, bottom: 0 }}>
                                    <XAxis dataKey="name" tick={{ fontSize: 11, fill: '#64748b' }} axisLine={false} tickLine={false} />
                                    <YAxis tick={{ fontSize: 11, fill: '#64748b' }} axisLine={false} tickLine={false} allowDecimals={false} />
                                    <Tooltip cursor={{ fill: 'rgba(154,0,54,0.05)' }} contentStyle={tooltipStyle} />
                                    <Bar dataKey="value" radius={[6, 6, 0, 0]} isAnimationActive={anim}>
                                        {stats.statusData.map((_, i) => (
                                            <Cell key={i} fill={PALETTE[i % PALETTE.length]} />
                                        ))}
                                    </Bar>
                                </BarChart>
                            </ResponsiveContainer>
                        </div>
                    </Card>

                    {/* KPI */}
                    <Card title="KPI vận hành" icon={TrendingUp}>
                        <div className="grid grid-cols-2 gap-2">
                            {[
                                { label: 'On-Time Delivery', value: 85, fill: '#9A0036' },
                                { label: 'Hiệu suất vận hành', value: 40, fill: '#E11D48' },
                            ].map((kpi) => (
                                <div key={kpi.label} className="text-center">
                                    <div className="relative mx-auto h-28 w-28">
                                        <ResponsiveContainer width="100%" height="100%">
                                            <RadialBarChart innerRadius="70%" outerRadius="100%" data={[{ value: kpi.value, fill: kpi.fill }]} startAngle={90} endAngle={-270}>
                                                <PolarAngleAxis type="number" domain={[0, 100]} tick={false} />
                                                <RadialBar background dataKey="value" cornerRadius={20} isAnimationActive={anim} />
                                            </RadialBarChart>
                                        </ResponsiveContainer>
                                        <span className="absolute inset-0 flex items-center justify-center text-xl font-bold tabular-nums text-secondary">
                                            {kpi.value}%
                                        </span>
                                    </div>
                                    <p className="mt-1 text-xs text-secondary/60">{kpi.label}</p>
                                </div>
                            ))}
                        </div>
                    </Card>

                    {/* Tiến độ dự án */}
                    <Card title="Tiến độ dự án" icon={Activity} className="lg:col-span-2">
                        <div className="mb-2 text-sm text-secondary/55">
                            Trung bình <span className="font-bold text-secondary">{stats.avgProgress}%</span> · top 6 dự án
                        </div>
                        {stats.topProjects.length === 0 ? (
                            <EmptyState />
                        ) : (
                            <div className="h-56">
                                <ResponsiveContainer width="100%" height="100%">
                                    <BarChart layout="vertical" data={stats.topProjects} margin={{ top: 0, right: 16, left: 10, bottom: 0 }}>
                                        <XAxis type="number" domain={[0, 100]} hide />
                                        <YAxis type="category" dataKey="name" width={110} tick={{ fontSize: 12, fill: '#475569' }} axisLine={false} tickLine={false} />
                                        <Tooltip cursor={{ fill: 'rgba(154,0,54,0.05)' }} contentStyle={tooltipStyle} formatter={(v) => [`${v}%`, 'Tiến độ']} />
                                        <Bar dataKey="progress" radius={[0, 6, 6, 0]} barSize={16} isAnimationActive={anim}>
                                            {stats.topProjects.map((_, i) => (
                                                <Cell key={i} fill={PALETTE[i % PALETTE.length]} />
                                            ))}
                                        </Bar>
                                    </BarChart>
                                </ResponsiveContainer>
                            </div>
                        )}
                    </Card>

                    {/* Đổi mới sáng tạo */}
                    <Card title="Đổi mới sáng tạo" icon={Sparkles}>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="rounded-xl bg-primary/5 p-4">
                                <p className="text-3xl font-bold tabular-nums text-primary">{aiCapabilities.length}</p>
                                <p className="text-xs text-secondary/60">Năng lực AI</p>
                            </div>
                            <div className="rounded-xl bg-accent/5 p-4">
                                <p className="text-3xl font-bold tabular-nums text-accent">{stats.aiProjects}</p>
                                <p className="text-xs text-secondary/60">Dự án ứng dụng AI</p>
                            </div>
                        </div>
                        <ul className="mt-4 space-y-2">
                            {aiCapabilities.slice(0, 3).map((c) => (
                                <li key={c.id} className="flex items-center gap-2 text-sm text-secondary/70">
                                    <span className="h-1.5 w-1.5 rounded-full bg-primary" />
                                    {c.title}
                                </li>
                            ))}
                        </ul>
                    </Card>

                    {/* Công nghệ sử dụng */}
                    <Card title="Công nghệ sử dụng" icon={Cpu} className="md:col-span-2 lg:col-span-3">
                        <div className="flex flex-col gap-6 lg:flex-row lg:items-center">
                            <div className="flex shrink-0 gap-6">
                                <div>
                                    <p className="text-4xl font-bold tabular-nums text-secondary">{technologies.length}</p>
                                    <p className="text-sm text-secondary/55">công nghệ</p>
                                </div>
                                <div>
                                    <p className="text-4xl font-bold tabular-nums text-emerald-600">{stats.techInUse}</p>
                                    <p className="text-sm text-secondary/55">đang dùng</p>
                                </div>
                            </div>
                            <div className="h-40 flex-1">
                                <ResponsiveContainer width="100%" height="100%">
                                    <BarChart data={stats.techByCat} margin={{ top: 8, right: 8, left: -20, bottom: 0 }}>
                                        <XAxis dataKey="name" tickFormatter={(v) => catLabels[v] ?? v} tick={{ fontSize: 11, fill: '#64748b' }} axisLine={false} tickLine={false} />
                                        <YAxis tick={{ fontSize: 11, fill: '#64748b' }} axisLine={false} tickLine={false} allowDecimals={false} />
                                        <Tooltip cursor={{ fill: 'rgba(154,0,54,0.05)' }} contentStyle={tooltipStyle} labelFormatter={(v) => catLabels[v] ?? v} />
                                        <Bar dataKey="value" radius={[6, 6, 0, 0]} barSize={36} isAnimationActive={anim}>
                                            {stats.techByCat.map((_, i) => (
                                                <Cell key={i} fill={PALETTE[i % PALETTE.length]} />
                                            ))}
                                        </Bar>
                                    </BarChart>
                                </ResponsiveContainer>
                            </div>
                        </div>
                    </Card>
                </div>
            </div>
        </section>
    );
}
