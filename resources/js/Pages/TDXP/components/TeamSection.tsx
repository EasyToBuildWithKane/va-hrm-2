import { useState } from 'react';
import { motion } from 'framer-motion';
import { teamMembers } from '@/data/team';
import type { TeamMember } from '@/types/tdxp';
import { MemberCard } from './MemberCard';
import { MemberDrawer } from './MemberDrawer';
import { EmptyState } from './EmptyState';
import { useToast } from '@/components/ui/Toast';
import { Users } from 'lucide-react';
import type { TeamBranch } from '@/types/tdxp';

const filters: { key: 'all' | TeamBranch; label: string }[] = [
    { key: 'all', label: 'Tất cả' },
    { key: 'software', label: 'Phần Mềm' },
    { key: 'hardware', label: 'Phần Cứng' },
];

/** Chỉ hiển thị thành viên có gắn nhánh team (loại trừ cấp lãnh đạo phòng — chỉ xuất hiện ở sơ đồ tổ chức). */
const displayMembers = teamMembers.filter((m) => m.team);

export function TeamSection() {
    const [filter, setFilter] = useState<'all' | TeamBranch>('all');
    const [selected, setSelected] = useState<TeamMember | null>(null);
    const [drawerOpen, setDrawerOpen] = useState(false);
    const toast = useToast();

    const filtered = filter === 'all' ? displayMembers : displayMembers.filter((m) => m.team === filter);

    const handleSelect = (member: TeamMember) => {
        setSelected(member);
        setDrawerOpen(true);
        toast({ title: `Hồ sơ ${member.name}`, description: member.role });
    };

    return (
        <section id="team" className="scroll-mt-24 py-20 md:py-28">
            <div className="mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <div className="max-w-2xl">
                        <p className="text-sm font-bold tracking-[0.2em] text-accent">ĐỘI NGŨ</p>
                        <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">Thành viên đội ngũ</h2>
                        <p className="mt-3 text-white/70">
                            Con người và năng lực đằng sau từng hệ thống chúng tôi xây dựng.
                        </p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        {filters.map((f) => (
                            <button
                                key={f.key}
                                type="button"
                                onClick={() => setFilter(f.key)}
                                aria-pressed={filter === f.key}
                                className={`rounded-full px-4 py-2 text-sm font-medium transition ${
                                    filter === f.key
                                        ? 'bg-primary text-white'
                                        : 'bg-white text-secondary/70 hover:bg-secondary/5'
                                }`}
                            >
                                {f.label}
                            </button>
                        ))}
                    </div>
                </div>
                {filtered.length === 0 ? (
                    <EmptyState
                        icon={Users}
                        title="Không có thành viên phù hợp"
                        description="Thử chọn nhánh khác để xem các thành viên."
                    />
                ) : (
                    <motion.div layout className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {filtered.map((member) => (
                            <MemberCard key={member.id} member={member} onSelect={handleSelect} />
                        ))}
                    </motion.div>
                )}
            </div>
            <MemberDrawer
                member={selected}
                open={drawerOpen}
                onOpenChange={(o) => {
                    setDrawerOpen(o);
                    if (!o) setTimeout(() => setSelected(null), 300);
                }}
            />
        </section>
    );
}
