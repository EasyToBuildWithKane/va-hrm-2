import { useRef, useState } from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { FlaskConical, Rocket, Activity, Clock, Users, ChevronLeft, ChevronRight, type LucideIcon } from 'lucide-react';
import { projects } from '@/data/projects';
import { teamMembers } from '@/data/team';
import type { Project, ProjectGroup } from '@/types/tdxp';
import { Badge } from '@/components/ui/Badge';
import { TechBackdrop } from './TechBackdrop';
import { cn } from '@/lib/utils';

const statusMeta: Record<Project['status'], { label: string; cls: string }> = {
    active: { label: 'Đang triển khai', cls: 'bg-amber-400/15 text-amber-300' },
    completed: { label: 'Đang vận hành', cls: 'bg-emerald-400/15 text-emerald-300' },
    planning: { label: 'Lên kế hoạch', cls: 'bg-sky-400/15 text-sky-300' },
};

interface GroupMeta {
    id: ProjectGroup;
    title: string;
    tagline: string;
    icon: LucideIcon;
    accent: string;
    glow: string;
}

const GROUPS: GroupMeta[] = [
    {
        id: 'rnd',
        title: 'Nghiên cứu & Phát triển',
        tagline: 'AI · Automation · Vibe Coding · Agent AI · đổi mới nội bộ.',
        icon: FlaskConical,
        accent: '#C3004A',
        glow: '#ff7eb3',
    },
    {
        id: 'delivery',
        title: 'Triển khai & Nghiệm thu',
        tagline: 'Các dự án đang triển khai với tiến độ, đội ngũ và mốc thời gian rõ ràng.',
        icon: Rocket,
        accent: '#9A0036',
        glow: '#ff5c8a',
    },
    {
        id: 'operations',
        title: 'Vận hành & Cải tiến',
        tagline: 'Các hệ thống đang vận hành — KPI, uptime và hiệu suất.',
        icon: Activity,
        accent: '#102A43',
        glow: '#7cc4ff',
    },
];

function ProjectCard({ project, index, accent, glow }: { project: Project; index: number; accent: string; glow: string }) {
    const status = statusMeta[project.status];
    const team = teamMembers.filter((m) => project.teamMemberIds.includes(m.id)).slice(0, 4);
    const isOps = project.group === 'operations';
    const pct = project.uptime ?? project.progress;

    return (
        <motion.article
            className="flex w-[300px] shrink-0 snap-start flex-col sm:w-[380px]"
            initial={{ opacity: 0, y: 28 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, delay: Math.min(index * 0.06, 0.36), ease: [0.22, 1, 0.36, 1] }}
        >
            {/* Mốc thời gian trên trục */}
            <div className="mb-5 flex items-center gap-3">
                <span className="h-3.5 w-3.5 shrink-0 rounded-full ring-4 ring-secondary" style={{ background: glow }} />
                <span className="text-sm font-bold" style={{ color: glow }}>
                    {project.timeline ?? '—'}
                </span>
                <span className="h-px flex-1 bg-gradient-to-r from-white/25 to-transparent" />
            </div>

            <div className="light-beam tech-border group flex min-h-[460px] flex-1 flex-col rounded-2xl border border-white/10 bg-white/[0.05] p-6 backdrop-blur-md transition duration-300 hover:border-white/25 hover:shadow-[0_0_36px_rgba(255,92,138,0.22)]">
                <div className="flex items-start justify-between gap-3">
                    <span className={cn('inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold', status.cls)}>
                        {status.label}
                    </span>
                    <div className="text-right">
                        <p className="text-2xl font-bold" style={{ color: glow }}>
                            {pct}%
                        </p>
                        <p className="text-[10px] uppercase tracking-wide text-white/40">{isOps ? 'uptime' : 'tiến độ'}</p>
                    </div>
                </div>

                <h3 className="mt-3 text-xl font-bold text-white">{project.name}</h3>
                <p className="text-sm font-medium" style={{ color: glow }}>
                    {project.client}
                </p>
                <p className="mt-3 text-sm leading-relaxed text-white/70">{project.description}</p>
                <p className="mt-4 text-sm font-medium text-white">
                    {isOps ? 'KPI: ' : 'Kết quả: '}
                    <span className="font-normal text-white/65">{isOps ? project.kpi : project.results}</span>
                </p>

                <div className="mt-4 h-1.5 overflow-hidden rounded-full bg-white/10">
                    <motion.span
                        className="block h-full rounded-full"
                        style={{ background: `linear-gradient(90deg, ${accent}, ${glow})` }}
                        initial={{ width: 0 }}
                        whileInView={{ width: `${pct}%` }}
                        viewport={{ once: true }}
                        transition={{ duration: 1.2, ease: [0.22, 1, 0.36, 1] }}
                    />
                </div>

                <div className="mt-4 flex flex-wrap gap-2">
                    {project.technologies.map((t) => (
                        <Badge key={t} variant="outline">
                            {t}
                        </Badge>
                    ))}
                </div>

                <div className="mt-auto flex items-center justify-between gap-2 pt-6">
                    <div className="flex items-center gap-2">
                        <Users className="h-3.5 w-3.5 text-white/40" />
                        <div className="flex -space-x-2">
                            {team.map((m) => (
                                <span
                                    key={m.id}
                                    title={m.name}
                                    className="flex h-8 w-8 items-end justify-center overflow-hidden rounded-full border-2 border-white/30"
                                    style={{ background: 'radial-gradient(120% 120% at 50% 0%, #c3004a, #6d0026)' }}
                                >
                                    <img src={m.avatar} alt={m.name} loading="lazy" decoding="async" className="h-[150%] w-auto max-w-none object-contain object-bottom" />
                                </span>
                            ))}
                        </div>
                    </div>
                    {project.timeline && (
                        <span className="flex items-center gap-1 text-xs text-white/45">
                            <Clock className="h-3.5 w-3.5" />
                            {project.timeline}
                        </span>
                    )}
                </div>
            </div>
        </motion.article>
    );
}

export function ProjectTimeline() {
    const [active, setActive] = useState<ProjectGroup>('rnd');
    const trackRef = useRef<HTMLDivElement>(null);

    const meta = GROUPS.find((g) => g.id === active)!;
    const list = projects.filter((p) => p.group === active);
    const slide = (dir: 1 | -1) => trackRef.current?.scrollBy({ left: dir * 400, behavior: 'smooth' });

    return (
        <section id="projects" className="relative scroll-mt-24 overflow-hidden py-20 md:py-28">
            <TechBackdrop />
            <div className="relative mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-8 max-w-2xl">
                    <p className="text-sm font-bold tracking-[0.25em] text-accent">DỰ ÁN</p>
                    <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">Dự án đang triển khai</h2>
                    <p className="mt-3 text-white/70">
                        Dòng thời gian theo ba nhóm: nghiên cứu &amp; phát triển, triển khai &amp; nghiệm thu, vận hành &amp; cải tiến.
                    </p>
                </div>

                {/* Tabs 3 nhóm — viền active trượt mượt bằng layoutId */}
                <div className="mb-6 flex flex-wrap gap-2">
                    {GROUPS.map((g) => {
                        const Icon = g.icon;
                        const isActive = g.id === active;
                        return (
                            <button
                                key={g.id}
                                type="button"
                                onClick={() => setActive(g.id)}
                                aria-pressed={isActive}
                                className={cn(
                                    'relative flex items-center gap-2 rounded-full border px-4 py-2.5 text-sm font-semibold transition',
                                    isActive
                                        ? 'border-transparent text-white'
                                        : 'border-white/15 bg-white/5 text-white/70 hover:bg-white/10 hover:text-white',
                                )}
                            >
                                {isActive && (
                                    <motion.span
                                        layoutId="proj-tab-active"
                                        className="absolute inset-0 -z-10 rounded-full"
                                        style={{ background: g.accent, boxShadow: `0 0 24px ${g.glow}66` }}
                                        transition={{ type: 'spring', stiffness: 400, damping: 32 }}
                                    />
                                )}
                                <Icon className="h-4 w-4" />
                                {g.title}
                                <span className={cn('rounded-full px-2 py-0.5 text-[11px] font-bold', isActive ? 'bg-white/25' : 'bg-white/10 text-white/60')}>
                                    {projects.filter((p) => p.group === g.id).length}
                                </span>
                            </button>
                        );
                    })}
                </div>

                <div className="mb-6 flex items-center justify-between gap-4">
                    <p className="max-w-xl text-sm text-white/55">{meta.tagline}</p>
                    <div className="hidden shrink-0 gap-2 sm:flex">
                        <button
                            type="button"
                            onClick={() => slide(-1)}
                            aria-label="Dự án trước"
                            className="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white/70 transition hover:bg-white/15 hover:text-white"
                        >
                            <ChevronLeft className="h-5 w-5" />
                        </button>
                        <button
                            type="button"
                            onClick={() => slide(1)}
                            aria-label="Dự án tiếp theo"
                            className="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white/70 transition hover:bg-white/15 hover:text-white"
                        >
                            <ChevronRight className="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </div>

            {/* Track cuộn ngang theo nhóm đang chọn */}
            <AnimatePresence mode="wait">
                <motion.div
                    key={active}
                    ref={trackRef}
                    initial={{ opacity: 0, x: 24 }}
                    animate={{ opacity: 1, x: 0 }}
                    exit={{ opacity: 0, x: -16 }}
                    transition={{ duration: 0.3, ease: [0.22, 1, 0.36, 1] }}
                    className="no-scrollbar flex snap-x snap-mandatory items-stretch gap-6 overflow-x-auto scroll-smooth px-4 pb-4 md:px-8"
                >
                    {list.map((p, i) => (
                        <ProjectCard key={p.id} project={p} index={i} accent={meta.accent} glow={meta.glow} />
                    ))}
                    <div className="w-2 shrink-0" aria-hidden />
                </motion.div>
            </AnimatePresence>
        </section>
    );
}
