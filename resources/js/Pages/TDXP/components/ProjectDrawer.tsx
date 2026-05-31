import type { Project, ProjectGroup, TeamMember } from '@/types/tdxp';
import { teamMembers } from '@/data/team';
import { Drawer } from '@/components/ui/Drawer';
import { Badge } from '@/components/ui/Badge';
import { ProgressBar } from '@/components/ui/ProgressBar';
import { getLucideIcon } from '@/lib/lucide';
import { DetailSection, Stat, StatGrid } from './detailKit';
import { cn } from '@/lib/utils';

interface ProjectDrawerProps {
    project: Project | null;
    open: boolean;
    onOpenChange: (open: boolean) => void;
    /** Bấm vào thành viên → mở hồ sơ (điều hướng chéo giữa 2 panel). */
    onSelectMember?: (member: TeamMember) => void;
}

const groupStyle: Record<ProjectGroup, { icon: string; from: string; to: string; label: string }> = {
    rnd: { icon: 'FlaskConical', from: '#C3004A', to: '#3a0016', label: 'Nghiên cứu & Phát triển' },
    delivery: { icon: 'Rocket', from: '#9A0036', to: '#3a0016', label: 'Triển khai & Nghiệm thu' },
    operations: { icon: 'Activity', from: '#102A43', to: '#0d2438', label: 'Vận hành & Cải tiến' },
};

const statusMeta: Record<Project['status'], { label: string; cls: string }> = {
    active: { label: 'Đang triển khai', cls: 'bg-amber-400/15 text-amber-300' },
    completed: { label: 'Đang vận hành', cls: 'bg-emerald-400/15 text-emerald-300' },
    planning: { label: 'Lên kế hoạch', cls: 'bg-sky-400/15 text-sky-300' },
};

export function ProjectDrawer({ project, open, onOpenChange, onSelectMember }: ProjectDrawerProps) {
    if (!project) return null;

    const g = groupStyle[project.group];
    const status = statusMeta[project.status];
    const Icon = getLucideIcon(g.icon);
    const team = teamMembers.filter((m) => project.teamMemberIds.includes(m.id));
    const isOps = project.group === 'operations';
    const pct = project.uptime ?? project.progress;

    return (
        <Drawer open={open} onOpenChange={onOpenChange}>
            {/* Header banner */}
            <div className="relative h-40 overflow-hidden" style={{ background: `linear-gradient(135deg, ${g.from}, ${g.to})` }}>
                <div className="bg-mesh-animated absolute inset-0 opacity-30" />
                <div className="absolute inset-x-6 bottom-4 flex items-end gap-4 md:inset-x-8">
                    <span className="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-white backdrop-blur-sm">
                        <Icon className="h-8 w-8" />
                    </span>
                    <div className="pb-1">
                        <h2 className="text-2xl font-bold leading-tight text-white">{project.name}</h2>
                        <p className="text-sm text-white/80">{project.client}</p>
                    </div>
                </div>
            </div>

            <div className="space-y-7 px-6 pb-10 pt-6 md:px-8">
                <StatGrid>
                    <Stat label={isOps ? 'Uptime' : 'Tiến độ'} value={`${pct}%`} />
                    <Stat label="Thành viên" value={team.length} />
                    <Stat label="Công nghệ" value={project.technologies.length} />
                </StatGrid>

                <div className="flex flex-wrap items-center gap-2">
                    <span className={cn('inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold', status.cls)}>
                        {status.label}
                    </span>
                    <span className="text-sm text-white/55">{g.label}</span>
                    {project.timeline && <span className="text-sm text-white/45">· {project.timeline}</span>}
                </div>

                <DetailSection title="Mô tả" icon="FileText">
                    <p className="leading-relaxed text-white/75">{project.description}</p>
                </DetailSection>

                <DetailSection title={isOps ? 'KPI' : 'Kết quả'} icon="Target">
                    <p className="rounded-xl border border-white/10 bg-white/5 p-4 text-white/80">
                        {isOps ? project.kpi : project.results}
                    </p>
                </DetailSection>

                <DetailSection title={isOps ? 'Uptime' : 'Tiến độ'} icon="Activity">
                    <ProgressBar label={project.name} value={pct} />
                </DetailSection>

                <DetailSection title="Công nghệ" icon="Code2">
                    <div className="flex flex-wrap gap-2">
                        {project.technologies.map((t) => (
                            <Badge key={t} variant="outline">
                                {t}
                            </Badge>
                        ))}
                    </div>
                </DetailSection>

                {team.length > 0 && (
                    <DetailSection title="Đội ngũ thực hiện" icon="Users">
                        <div className="flex flex-wrap gap-2">
                            {team.map((m) => (
                                <button
                                    key={m.id}
                                    type="button"
                                    onClick={() => onSelectMember?.(m)}
                                    className="flex items-center gap-2 rounded-full border border-white/10 bg-white/5 py-1 pl-1 pr-3 transition hover:border-white/30 hover:bg-white/10"
                                >
                                    <span
                                        className="flex h-7 w-7 items-end justify-center overflow-hidden rounded-full"
                                        style={{ background: 'radial-gradient(120% 120% at 50% 0%, #c3004a, #6d0026)' }}
                                    >
                                        <img src={m.avatar} alt={m.name} loading="lazy" decoding="async" className="h-[150%] w-auto max-w-none object-contain object-bottom" />
                                    </span>
                                    <span className="text-sm text-white/80">{m.name}</span>
                                </button>
                            ))}
                        </div>
                    </DetailSection>
                )}
            </div>
        </Drawer>
    );
}
