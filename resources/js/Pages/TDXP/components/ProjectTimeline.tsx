import { useRef } from 'react';
import { motion } from 'framer-motion';
import { ChevronLeft, ChevronRight, Clock, Users } from 'lucide-react';
import { projects } from '@/data/projects';
import { teamMembers } from '@/data/team';
import type { Project } from '@/types/tdxp';
import { Badge } from '@/components/ui/Badge';
import { cn } from '@/lib/utils';

const statusMeta: Record<Project['status'], { label: string; dot: string; cls: string }> = {
    completed: { label: 'Đang vận hành', dot: 'bg-emerald-400', cls: 'bg-emerald-400/15 text-emerald-300' },
    active: { label: 'Đang triển khai', dot: 'bg-amber-400', cls: 'bg-amber-400/15 text-amber-300' },
    planning: { label: 'Lên kế hoạch', dot: 'bg-sky-400', cls: 'bg-sky-400/15 text-sky-300' },
};

// Sắp theo độ trưởng thành để đọc như một dòng thời gian: vận hành → triển khai → kế hoạch.
const statusOrder: Record<Project['status'], number> = { completed: 0, active: 1, planning: 2 };
const timeline = [...projects].sort((a, b) => statusOrder[a.status] - statusOrder[b.status]);

function ProjectNode({ project, index }: { project: Project; index: number }) {
    const status = statusMeta[project.status];
    const team = teamMembers.filter((m) => project.teamMemberIds.includes(m.id)).slice(0, 4);

    return (
        <motion.article
            className="relative w-[280px] shrink-0 snap-start sm:w-[340px]"
            initial={{ opacity: 0, y: 28 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, delay: Math.min(index * 0.06, 0.4), ease: [0.22, 1, 0.36, 1] }}
        >
            {/* Mốc thời gian trên trục */}
            <div className="mb-5 flex items-center gap-3">
                <span className={cn('h-3.5 w-3.5 shrink-0 rounded-full ring-4 ring-secondary', status.dot)} />
                <span className="text-sm font-bold text-glow">{project.timeline ?? '—'}</span>
            </div>

            <div className="light-beam group flex h-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-white/[0.05] p-6 backdrop-blur-md transition duration-300 hover:border-white/25 hover:shadow-[0_0_36px_rgba(255,92,138,0.22)]">
                <div className="flex items-start justify-between gap-3">
                    <span className={cn('inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold', status.cls)}>
                        {status.label}
                    </span>
                    {project.status === 'completed' && project.uptime != null ? (
                        <span className="text-right text-sm font-bold text-glow">
                            {project.uptime}%<span className="block text-[10px] font-normal uppercase text-white/40">uptime</span>
                        </span>
                    ) : (
                        <span className="text-right text-sm font-bold text-glow">
                            {project.progress}%<span className="block text-[10px] font-normal uppercase text-white/40">tiến độ</span>
                        </span>
                    )}
                </div>

                <h3 className="mt-3 text-xl font-bold text-white">{project.name}</h3>
                <p className="text-sm font-medium text-white/55">{project.client}</p>
                <p className="mt-3 text-sm leading-relaxed text-white/70">{project.description}</p>

                {/* Thanh tiến độ */}
                <div className="mt-4 h-1.5 overflow-hidden rounded-full bg-white/10">
                    <motion.span
                        className="block h-full rounded-full bg-gradient-to-r from-primary to-glow"
                        initial={{ width: 0 }}
                        whileInView={{ width: `${project.uptime ?? project.progress}%` }}
                        viewport={{ once: true }}
                        transition={{ duration: 1.2, ease: [0.22, 1, 0.36, 1] }}
                    />
                </div>

                <div className="mt-4 flex flex-wrap gap-2">
                    {project.technologies.slice(0, 4).map((t) => (
                        <Badge key={t} variant="outline">
                            {t}
                        </Badge>
                    ))}
                </div>

                <div className="mt-auto flex items-center justify-between gap-2 pt-5">
                    <div className="flex items-center gap-2">
                        <Users className="h-3.5 w-3.5 text-white/40" />
                        <div className="flex -space-x-2">
                            {team.map((m) => (
                                <span
                                    key={m.id}
                                    title={m.name}
                                    className="flex h-7 w-7 items-end justify-center overflow-hidden rounded-full border-2 border-white/30"
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
    const trackRef = useRef<HTMLDivElement>(null);
    const slide = (dir: 1 | -1) => trackRef.current?.scrollBy({ left: dir * 380, behavior: 'smooth' });

    return (
        <section id="projects" className="relative scroll-mt-24 overflow-hidden py-20 md:py-28">
            <div className="bg-mesh-animated pointer-events-none absolute inset-0 opacity-30" aria-hidden />
            <div className="relative mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <div className="max-w-2xl">
                        <p className="text-sm font-bold tracking-[0.25em] text-accent">DỰ ÁN</p>
                        <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">Dự án đang triển khai</h2>
                        <p className="mt-3 text-white/70">
                            Dòng thời gian các hệ thống — từ đang vận hành, đang triển khai đến lên kế hoạch. Cuộn ngang để khám phá.
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

            {/* Trục thời gian + track cuộn ngang */}
            <div className="relative">
                <div
                    ref={trackRef}
                    className="no-scrollbar flex snap-x snap-mandatory items-stretch gap-6 overflow-x-auto scroll-smooth px-4 pb-4 md:px-8"
                >
                    {/* Đường trục chạy ngang phía sau các mốc */}
                    <div className="pointer-events-none absolute left-0 right-0 top-[1.7rem] -z-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent" />
                    {timeline.map((p, i) => (
                        <ProjectNode key={p.id} project={p} index={i} />
                    ))}
                    <div className="w-2 shrink-0" aria-hidden />
                </div>
            </div>
        </section>
    );
}
