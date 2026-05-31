import { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import type { TeamMember } from '@/types/tdxp';
import { Drawer } from '@/components/ui/Drawer';
import { Badge } from '@/components/ui/Badge';
import { ProgressBar } from '@/components/ui/ProgressBar';
import { Img } from '@/components/ui/Img';
import { ChevronLeft, ChevronRight } from 'lucide-react';

interface MemberDrawerProps {
    member: TeamMember | null;
    open: boolean;
    onOpenChange: (open: boolean) => void;
}

export function MemberDrawer({ member, open, onOpenChange }: MemberDrawerProps) {
    const [galleryIndex, setGalleryIndex] = useState(0);

    if (!member) {
        return null;
    }

    const gallery = member.gallery;
    const prev = () => setGalleryIndex((i) => (i - 1 + gallery.length) % gallery.length);
    const next = () => setGalleryIndex((i) => (i + 1) % gallery.length);

    return (
        <Drawer open={open} onOpenChange={onOpenChange}>
            <div className="relative">
                <Img
                    src={member.coverImage}
                    alt=""
                    wrapperClassName="h-36 w-full md:h-44"
                    className="h-full w-full object-cover"
                />
                <div className="absolute -bottom-12 left-6 md:left-8">
                    <motion.img
                        layoutId={`member-avatar-${member.id}`}
                        src={member.avatar}
                        alt={`Ảnh đại diện ${member.name}`}
                        loading="lazy"
                        decoding="async"
                        width={96}
                        height={96}
                        className="h-24 w-24 rounded-2xl border-4 border-[#3a0016] object-cover shadow-xl"
                    />
                </div>
            </div>
            <div className="space-y-8 px-6 pb-10 pt-16 md:px-8">
                <header>
                    <h2 className="text-2xl font-bold text-white">{member.name}</h2>
                    <p className="text-glow">{member.role}</p>
                    <p className="mt-1 text-sm text-white/60">Kinh nghiệm: {member.experience}</p>
                    <blockquote className="mt-4 border-l-4 border-glow pl-4 text-white/80 italic">
                        &ldquo;{member.quote}&rdquo;
                    </blockquote>
                </header>

                <section>
                    <h3 className="text-sm font-bold uppercase tracking-wider text-glow/80">Giới thiệu</h3>
                    <p className="mt-2 text-white/80 leading-relaxed">{member.bio}</p>
                </section>

                <section>
                    <h3 className="text-sm font-bold uppercase tracking-wider text-glow/80">
                        Dự án hiện tại
                    </h3>
                    <ul className="mt-4 space-y-4">
                        {member.projects.map((p) => (
                            <li key={p.id} className="rounded-xl border border-white/10 bg-white/5 p-4">
                                <div className="flex justify-between gap-2">
                                    <span className="font-semibold text-white">{p.name}</span>
                                    <span className="text-xs text-white/50">Tiến độ: {p.progress}%</span>
                                </div>
                                <p className="mt-1 text-sm text-white/60">Vai trò: {p.role}</p>
                                <ProgressBar label="" value={p.progress} showLabel={false} className="mt-3" />
                            </li>
                        ))}
                    </ul>
                </section>

                <section>
                    <h3 className="text-sm font-bold uppercase tracking-wider text-glow/80">
                        Chuyên môn công nghệ
                    </h3>
                    <div className="mt-3 flex flex-wrap gap-2">
                        {member.skills.map((s) => (
                            <Badge key={s.name}>{s.name}</Badge>
                        ))}
                    </div>
                </section>

                <section>
                    <h3 className="text-sm font-bold uppercase tracking-wider text-glow/80">
                        Phân bổ công việc
                    </h3>
                    <div className="mt-4 space-y-3">
                        {member.workload.map((w) => (
                            <ProgressBar key={w.label} label={w.label} value={w.value} />
                        ))}
                    </div>
                </section>

                <section>
                    <h3 className="text-sm font-bold uppercase tracking-wider text-glow/80">Thành tựu</h3>
                    <ul className="mt-3 list-disc space-y-1 pl-5 text-sm text-secondary/80">
                        {member.achievements.map((a) => (
                            <li key={a}>{a}</li>
                        ))}
                    </ul>
                </section>

                <section>
                    <h3 className="text-sm font-bold uppercase tracking-wider text-glow/80">Hành trình sự nghiệp</h3>
                    <ol className="relative mt-4 space-y-4 border-l-2 border-primary/20 pl-6">
                        {member.careerTimeline.map((step) => (
                            <li key={step.title} className="relative">
                                <span className="absolute -left-[1.6rem] top-1 h-3 w-3 rounded-full bg-primary" />
                                <p className="font-medium text-secondary">{step.title}</p>
                                <p className="text-xs text-secondary/50">{step.period}</p>
                            </li>
                        ))}
                    </ol>
                </section>

                <section>
                    <h3 className="text-sm font-bold uppercase tracking-wider text-glow/80">Thư viện ảnh</h3>
                    <div className="relative mt-4 overflow-hidden rounded-xl">
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
                            className="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-white/90 p-2 shadow"
                            aria-label="Ảnh trước"
                        >
                            <ChevronLeft className="h-4 w-4" />
                        </button>
                        <button
                            type="button"
                            onClick={next}
                            className="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-white/90 p-2 shadow"
                            aria-label="Ảnh sau"
                        >
                            <ChevronRight className="h-4 w-4" />
                        </button>
                    </div>
                </section>

                <section className="rounded-2xl bg-gradient-to-br from-primary to-secondary p-8 text-center">
                    <p className="text-xs font-bold uppercase tracking-widest text-white/70">Phương châm</p>
                    <p className="mt-4 text-2xl font-bold leading-snug text-white md:text-3xl">
                        &ldquo;{member.motto}&rdquo;
                    </p>
                </section>
            </div>
        </Drawer>
    );
}
