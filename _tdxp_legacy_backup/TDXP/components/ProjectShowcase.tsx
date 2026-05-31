import { useEffect, useRef, useState } from 'react';
import { AnimatePresence, animate, motion, useMotionValue, useReducedMotion, useTransform } from 'framer-motion';
import {
    FlaskConical,
    Rocket,
    Activity,
    Clock,
    ChevronLeft,
    ChevronRight,
    type LucideIcon,
} from 'lucide-react';
import { projects } from '@/data/projects';
import { teamMembers } from '@/data/team';
import type { Project, ProjectGroup } from '@/types/tdxp';
import { Badge } from '@/components/ui/Badge';
import { cn } from '@/lib/utils';

const statusLabel: Record<Project['status'], { label: string; cls: string }> = {
    active: { label: 'Đang triển khai', cls: 'bg-amber-400/15 text-amber-300' },
    completed: { label: 'Đang vận hành', cls: 'bg-emerald-400/15 text-emerald-300' },
    planning: { label: 'Lên kế hoạch', cls: 'bg-sky-400/15 text-sky-300' },
};

interface GroupMeta {
    id: ProjectGroup;
    eyebrow: string;
    title: string;
    tagline: string;
    icon: LucideIcon;
    accent: string;
    /** Biến thể sáng để đọc rõ trên nền đỏ tối */
    glow: string;
}

const GROUPS: GroupMeta[] = [
    {
        id: 'rnd',
        eyebrow: 'R&D',
        title: 'Nghiên cứu & Phát triển',
        tagline: 'AI · Automation · Vibe Coding · Agent AI · Đổi mới nội bộ.',
        icon: FlaskConical,
        accent: '#C3004A',
        glow: '#ff7eb3',
    },
    {
        id: 'delivery',
        eyebrow: 'DELIVERY',
        title: 'Triển khai & Nghiệm thu',
        tagline: 'Các dự án đang triển khai với tiến độ, đội ngũ và mốc thời gian rõ ràng.',
        icon: Rocket,
        accent: '#9A0036',
        glow: '#ff5c8a',
    },
    {
        id: 'operations',
        eyebrow: 'OPERATIONS',
        title: 'Vận hành & Cải tiến',
        tagline: 'Các hệ thống đang vận hành — KPI, uptime và hiệu suất.',
        icon: Activity,
        accent: '#102A43',
        glow: '#7cc4ff',
    },
];

/** Vòng tiến độ tự đếm số 0 → progress khi xuất hiện. */
function ProgressRing({ progress, accent }: { progress: number; accent: string }) {
    const reduce = useReducedMotion();
    const mv = useMotionValue(reduce ? progress : 0);
    const [display, setDisplay] = useState(reduce ? progress : 0);
    const background = useTransform(mv, (v) => `conic-gradient(${accent} ${v * 3.6}deg, rgba(255,255,255,0.12) 0deg)`);

    useEffect(() => {
        if (reduce) {
            setDisplay(progress);
            mv.set(progress);
            return;
        }
        const controls = animate(mv, progress, {
            duration: 1.4,
            ease: [0.22, 1, 0.36, 1],
            onUpdate: (v) => setDisplay(Math.round(v)),
        });
        return () => controls.stop();
    }, [progress, mv, reduce]);

    return (
        <motion.div
            className="flex h-14 w-14 shrink-0 items-center justify-center rounded-full text-xs font-bold text-white"
            style={{ background }}
            aria-label={`Tiến độ ${progress}%`}
        >
            <span className="flex h-10 w-10 items-center justify-center rounded-full bg-[#3a0016] tabular-nums">
                {display}%
            </span>
        </motion.div>
    );
}

function ProjectCard({ project, accent, glow, index }: { project: Project; accent: string; glow: string; index: number }) {
    const team = teamMembers.filter((m) => project.teamMemberIds.includes(m.id)).slice(0, 4);
    const status = statusLabel[project.status];
    const isOps = project.group === 'operations';

    return (
        <motion.article
            className={cn(
                'group light-beam tech-border relative flex h-full w-[300px] shrink-0 snap-start flex-col overflow-hidden rounded-2xl border border-white/10 bg-white/[0.05] backdrop-blur-md sm:w-[360px]',
                'transition duration-300 hover:border-white/25 hover:shadow-[0_0_36px_rgba(255,92,138,0.28)]',
            )}
            initial={{ opacity: 0, y: 28 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-60px' }}
            transition={{ duration: 0.5, delay: Math.min(index * 0.08, 0.4), ease: [0.22, 1, 0.36, 1] }}
            whileHover={{ y: -8 }}
        >
            <div className="flex items-start justify-between gap-4 border-b border-white/10 p-6">
                <div className="min-w-0">
                    <span className={cn('inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold', status.cls)}>
                        {status.label}
                    </span>
                    <h3 className="mt-2 text-xl font-bold text-white">{project.name}</h3>
                    <p className="text-sm font-medium" style={{ color: glow }}>
                        {project.client}
                    </p>
                </div>
                {isOps && project.uptime != null ? (
                    <div className="shrink-0 text-right">
                        <p className="text-2xl font-bold" style={{ color: glow }}>
                            {project.uptime}%
                        </p>
                        <p className="text-[11px] uppercase tracking-wide text-white/45">Uptime</p>
                    </div>
                ) : (
                    <ProgressRing progress={project.progress} accent={accent} />
                )}
            </div>
            <div className="flex flex-1 flex-col p-6">
                <p className="text-sm leading-relaxed text-white/70">{project.description}</p>
                <p className="mt-4 text-sm font-medium text-white">
                    {isOps ? 'KPI: ' : 'Kết quả: '}
                    <span className="font-normal text-white/65">{isOps ? project.kpi : project.results}</span>
                </p>
                <div className="mt-4 flex flex-wrap gap-2">
                    {project.technologies.map((t, i) => (
                        <motion.span
                            key={t}
                            initial={{ opacity: 0, scale: 0.85 }}
                            whileInView={{ opacity: 1, scale: 1 }}
                            viewport={{ once: true }}
                            transition={{ delay: 0.2 + i * 0.05, duration: 0.3 }}
                        >
                            <Badge variant="outline">{t}</Badge>
                        </motion.span>
                    ))}
                </div>
                <div className="mt-auto flex flex-wrap items-center justify-between gap-3 pt-6">
                    <div className="flex items-center gap-2">
                        <span className="text-xs text-white/50">Đội ngũ:</span>
                        <div className="flex -space-x-2">
                            {team.map((m) => (
                                <span
                                    key={m.id}
                                    title={m.name}
                                    className="flex h-8 w-8 items-end justify-center overflow-hidden rounded-full border-2 border-white/30"
                                    style={{ background: 'radial-gradient(120% 120% at 50% 0%, #c3004a, #6d0026)' }}
                                >
                                    <img
                                        src={m.avatar}
                                        alt={m.name}
                                        loading="lazy"
                                        decoding="async"
                                        className="h-[150%] w-auto max-w-none object-contain object-bottom"
                                    />
                                </span>
                            ))}
                        </div>
                    </div>
                    {project.timeline && (
                        <span className="flex items-center gap-1 text-xs text-white/50">
                            <Clock className="h-3.5 w-3.5" />
                            {project.timeline}
                        </span>
                    )}
                </div>
            </div>
        </motion.article>
    );
}

export function ProjectShowcase() {
    const [active, setActive] = useState<ProjectGroup>('rnd');
    const trackRef = useRef<HTMLDivElement>(null);

    const meta = GROUPS.find((g) => g.id === active)!;
    const list = projects.filter((p) => p.group === active);
    const avgProgress = list.length ? Math.round(list.reduce((s, p) => s + p.progress, 0) / list.length) : 0;
    const opsAvgUptime =
        active === 'operations' && list.length
            ? (list.reduce((s, p) => s + (p.uptime ?? 0), 0) / list.length).toFixed(1)
            : null;

    const slide = (dir: 1 | -1) => {
        trackRef.current?.scrollBy({ left: dir * 380, behavior: 'smooth' });
    };

    return (
        <section id="projects" className="relative scroll-mt-24 overflow-hidden py-20 md:py-28">
            {/* Lớp lưới tech động + quầng sáng */}
            <div className="bg-mesh-animated pointer-events-none absolute inset-0 opacity-60" aria-hidden />
            <div
                className="animate-glow pointer-events-none absolute -right-32 top-10 h-80 w-80 rounded-full blur-3xl"
                style={{ background: 'radial-gradient(circle, rgba(255,92,138,0.35), transparent 70%)' }}
                aria-hidden
            />
            <div className="relative mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-8 max-w-2xl">
                    <p className="text-sm font-bold tracking-[0.25em] text-glow">DỰ ÁN</p>
                    <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">Hệ sinh thái dự án</h2>
                    <p className="mt-3 text-white/65">
                        Từ nghiên cứu &amp; phát triển, triển khai nghiệm thu đến vận hành &amp; cải tiến liên tục.
                    </p>
                </div>

                {/* Tabs nhóm dự án — viền active trượt mượt bằng layoutId */}
                <div className="mb-8 flex flex-wrap gap-2">
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
                                        layoutId="project-tab-active"
                                        className="absolute inset-0 -z-10 rounded-full shadow-lg"
                                        style={{ background: g.accent, boxShadow: `0 0 24px ${g.glow}66` }}
                                        transition={{ type: 'spring', stiffness: 400, damping: 32 }}
                                    />
                                )}
                                <motion.span animate={{ scale: isActive ? 1.15 : 1 }} className="inline-flex">
                                    <Icon className="h-4 w-4" />
                                </motion.span>
                                {g.title}
                                <span
                                    className={cn(
                                        'rounded-full px-2 py-0.5 text-[11px] font-bold',
                                        isActive ? 'bg-white/25' : 'bg-white/10 text-white/60',
                                    )}
                                >
                                    {projects.filter((p) => p.group === g.id).length}
                                </span>
                            </button>
                        );
                    })}
                </div>

                <AnimatePresence mode="wait">
                    <motion.div
                        key={active}
                        initial={{ opacity: 0, y: 16 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: -8 }}
                        transition={{ duration: 0.3, ease: [0.22, 1, 0.36, 1] }}
                        className="glass-card rounded-3xl p-6 md:p-8"
                    >
                        {/* Header + stats + nút điều hướng slider */}
                        <div className="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p className="text-xs font-bold tracking-[0.2em]" style={{ color: meta.glow }}>
                                    {meta.eyebrow}
                                </p>
                                <p className="mt-1 max-w-xl text-sm text-white/60">{meta.tagline}</p>
                            </div>
                            <div className="flex items-center gap-6">
                                <div className="text-center">
                                    <p className="text-2xl font-bold" style={{ color: meta.glow }}>
                                        {list.length}
                                    </p>
                                    <p className="text-[11px] uppercase tracking-wide text-white/45">Dự án</p>
                                </div>
                                <div className="text-center">
                                    <p className="text-2xl font-bold" style={{ color: meta.glow }}>
                                        {opsAvgUptime ? `${opsAvgUptime}%` : `${avgProgress}%`}
                                    </p>
                                    <p className="text-[11px] uppercase tracking-wide text-white/45">
                                        {opsAvgUptime ? 'Uptime TB' : 'Tiến độ TB'}
                                    </p>
                                </div>
                                <div className="hidden gap-2 sm:flex">
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

                        {/* Slider / swiper: scroll-snap + vuốt trên cảm ứng */}
                        <div
                            ref={trackRef}
                            className="no-scrollbar -mx-1 flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth px-1 pb-2"
                        >
                            {list.map((p, i) => (
                                <ProjectCard key={p.id} project={p} accent={meta.accent} glow={meta.glow} index={i} />
                            ))}
                        </div>
                    </motion.div>
                </AnimatePresence>
            </div>
        </section>
    );
}
