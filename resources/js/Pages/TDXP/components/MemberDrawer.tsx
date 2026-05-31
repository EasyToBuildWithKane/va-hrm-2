import { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import type { TeamMember } from '@/types/tdxp';
import { Drawer } from '@/components/ui/Drawer';
import { Badge } from '@/components/ui/Badge';
import { ProgressBar } from '@/components/ui/ProgressBar';
import { Img } from '@/components/ui/Img';
import { DetailSection, Stat, StatGrid } from './detailKit';

interface MemberDrawerProps {
    member: TeamMember | null;
    open: boolean;
    onOpenChange: (open: boolean) => void;
}

export function MemberDrawer({ member, open, onOpenChange }: MemberDrawerProps) {
    const [galleryIndex, setGalleryIndex] = useState(0);

    if (!member) return null;

    const gallery = member.gallery;
    const prev = () => setGalleryIndex((i) => (i - 1 + gallery.length) % gallery.length);
    const next = () => setGalleryIndex((i) => (i + 1) % gallery.length);

    return (
        <Drawer open={open} onOpenChange={onOpenChange}>
            {/* Header banner */}
            <div className="relative">
                <Img src={member.coverImage} alt="" wrapperClassName="h-40 w-full" className="h-full w-full object-cover" />
                <div className="absolute inset-0 bg-gradient-to-t from-[#3a0016] via-[#3a0016]/60 to-transparent" />
                <div className="absolute inset-x-6 bottom-4 flex items-end gap-4 md:inset-x-8">
                    <span
                        className="flex h-20 w-20 shrink-0 items-end justify-center overflow-hidden rounded-2xl border-2 border-white/20 shadow-xl"
                        style={{ background: 'radial-gradient(120% 120% at 50% 0%, #c3004a, #6d0026)' }}
                    >
                        <motion.img
                            layoutId={`member-avatar-${member.id}`}
                            src={member.avatar}
                            alt={`Ảnh đại diện ${member.name}`}
                            loading="lazy"
                            decoding="async"
                            className="h-[130%] w-auto object-contain object-bottom"
                        />
                    </span>
                    <div className="pb-1">
                        <h2 className="text-2xl font-bold leading-tight text-white">{member.name}</h2>
                        <p className="text-sm font-medium text-glow">{member.role}</p>
                    </div>
                </div>
            </div>

            <div className="space-y-7 px-6 pb-10 pt-6 md:px-8">
                <StatGrid>
                    <Stat label="Kinh nghiệm" value={member.experience} />
                    <Stat label="Dự án" value={member.activeProjectCount} />
                    <Stat label="Kỹ năng" value={member.skills.length} />
                </StatGrid>

                <blockquote className="border-l-2 border-glow pl-4 text-white/80 italic">
                    &ldquo;{member.quote}&rdquo;
                </blockquote>

                <DetailSection title="Giới thiệu" icon="User">
                    <p className="leading-relaxed text-white/75">{member.bio}</p>
                </DetailSection>

                {member.projects.length > 0 && (
                    <DetailSection title="Dự án hiện tại" icon="FolderKanban">
                        <ul className="space-y-3">
                            {member.projects.map((p) => (
                                <li key={p.id} className="rounded-xl border border-white/10 bg-white/5 p-4">
                                    <div className="flex items-center justify-between gap-2">
                                        <span className="font-semibold text-white">{p.name}</span>
                                        <span className="text-xs text-white/50">{p.progress}%</span>
                                    </div>
                                    <p className="mt-1 text-sm text-white/55">Vai trò: {p.role}</p>
                                    <ProgressBar label="" value={p.progress} showLabel={false} className="mt-3" />
                                </li>
                            ))}
                        </ul>
                    </DetailSection>
                )}

                <DetailSection title="Chuyên môn công nghệ" icon="Code2">
                    <div className="flex flex-wrap gap-2">
                        {member.skills.map((s) => (
                            <Badge key={s.name}>{s.name}</Badge>
                        ))}
                    </div>
                </DetailSection>

                <DetailSection title="Phân bổ công việc" icon="Activity">
                    <div className="space-y-3">
                        {member.workload.map((w) => (
                            <ProgressBar key={w.label} label={w.label} value={w.value} />
                        ))}
                    </div>
                </DetailSection>

                <DetailSection title="Thành tựu" icon="Award">
                    <ul className="space-y-2">
                        {member.achievements.map((a) => (
                            <li key={a} className="flex gap-2 text-sm text-white/75">
                                <span className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-glow" />
                                {a}
                            </li>
                        ))}
                    </ul>
                </DetailSection>

                <DetailSection title="Hành trình sự nghiệp" icon="Milestone">
                    <ol className="relative space-y-4 border-l-2 border-white/15 pl-6">
                        {member.careerTimeline.map((step) => (
                            <li key={step.title} className="relative">
                                <span className="absolute -left-[1.65rem] top-1 h-3 w-3 rounded-full bg-glow ring-4 ring-[#3a0016]" />
                                <p className="font-medium text-white">{step.title}</p>
                                <p className="text-xs text-white/50">{step.period}</p>
                            </li>
                        ))}
                    </ol>
                </DetailSection>

                <DetailSection title="Thư viện ảnh" icon="Images">
                    <div className="relative overflow-hidden rounded-xl border border-white/10">
                        <AnimatePresence mode="wait">
                            <motion.img
                                key={galleryIndex}
                                src={gallery[galleryIndex]}
                                alt={`Ảnh ${galleryIndex + 1} của ${member.name}`}
                                loading="lazy"
                                decoding="async"
                                className="aspect-video w-full object-cover"
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                exit={{ opacity: 0, x: -20 }}
                                transition={{ duration: 0.3 }}
                            />
                        </AnimatePresence>
                        <button
                            type="button"
                            onClick={prev}
                            className="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-2 text-white backdrop-blur-sm transition hover:bg-white/25"
                            aria-label="Ảnh trước"
                        >
                            <ChevronLeft className="h-4 w-4" />
                        </button>
                        <button
                            type="button"
                            onClick={next}
                            className="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-2 text-white backdrop-blur-sm transition hover:bg-white/25"
                            aria-label="Ảnh sau"
                        >
                            <ChevronRight className="h-4 w-4" />
                        </button>
                        <span className="absolute bottom-2 left-1/2 -translate-x-1/2 rounded-full bg-black/40 px-2 py-0.5 text-[11px] text-white/80 backdrop-blur-sm">
                            {galleryIndex + 1}/{gallery.length}
                        </span>
                    </div>
                </DetailSection>

                <section className="rounded-2xl bg-gradient-to-br from-primary to-secondary p-8 text-center">
                    <p className="text-xs font-bold uppercase tracking-widest text-white/70">Phương châm</p>
                    <p className="mt-4 text-2xl font-bold leading-snug text-white md:text-3xl">&ldquo;{member.motto}&rdquo;</p>
                </section>
            </div>
        </Drawer>
    );
}
