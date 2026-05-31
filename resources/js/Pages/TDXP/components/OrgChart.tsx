import { useMemo, useState } from 'react';
import { motion } from 'framer-motion';
import { orgNodes, orgEdges, deptColors, deptLabels } from '@/data/organization';
import { getMemberById } from '@/data/team';
import type { TeamMember } from '@/types/tdxp';
import { MemberDrawer } from './MemberDrawer';
import { TechBackdrop } from './TechBackdrop';
import { cn } from '@/lib/utils';

type OrgNode = (typeof orgNodes)[number];

export function OrgChart() {
    const [selected, setSelected] = useState<TeamMember | null>(null);
    const [open, setOpen] = useState(false);

    const nodeMap = useMemo(() => {
        const m: Record<string, OrgNode> = {};
        orgNodes.forEach((n) => (m[n.id] = n));
        return m;
    }, []);

    const childrenMap = useMemo(() => {
        const m: Record<string, string[]> = {};
        orgEdges.forEach((e) => {
            (m[e.source] ??= []).push(e.target);
        });
        return m;
    }, []);

    const root = useMemo(() => orgNodes.find((n) => n.data.kind === 'root') ?? orgNodes[0], []);

    const select = (member?: TeamMember) => {
        if (!member) return;
        setSelected(member);
        setOpen(true);
    };

    const renderNode = (id: string): React.ReactNode => {
        const node = nodeMap[id];
        if (!node) return null;
        const kids = childrenMap[id] ?? [];
        const member = getMemberById(id.replace('emp-', ''));
        const color = deptColors[node.data.dept] ?? '#9A0036';
        const isRoot = node.data.kind === 'root';

        return (
            <li key={id}>
                <motion.button
                    type="button"
                    onClick={() => select(member)}
                    initial={{ opacity: 0, y: 16 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, margin: '-30px' }}
                    transition={{ duration: 0.4, ease: [0.22, 1, 0.36, 1] }}
                    whileHover={{ y: -4 }}
                    className={cn(
                        'group relative inline-flex flex-col items-center gap-1.5 rounded-2xl border bg-white/[0.05] px-4 py-4 backdrop-blur-md transition',
                        'hover:border-white/40 hover:shadow-[0_0_28px_rgba(255,92,138,0.25)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-glow/50',
                        isRoot ? 'w-48 border-white/30' : 'w-40 border-white/15',
                    )}
                    style={{ borderTopColor: color, borderTopWidth: 3 }}
                >
                    <span
                        className="flex h-16 w-16 items-end justify-center overflow-hidden rounded-full ring-2 ring-white/15"
                        style={{ background: `radial-gradient(120% 120% at 50% 0%, ${color}, #3a0016)` }}
                    >
                        {member?.avatar && (
                            <img
                                src={member.avatar}
                                alt={member.name}
                                loading="lazy"
                                decoding="async"
                                className="h-[150%] w-auto max-w-none object-contain object-bottom"
                            />
                        )}
                    </span>
                    <span className={cn('mt-1 font-bold leading-tight text-white', isRoot ? 'text-sm' : 'text-[13px]')}>
                        {node.data.label}
                    </span>
                    <span className="text-[11px] leading-tight text-white/55">{node.data.subtitle}</span>
                    <span
                        className="mt-1 inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold text-white"
                        style={{ background: `${color}33` }}
                    >
                        {deptLabels[node.data.dept] ?? node.data.dept}
                    </span>
                </motion.button>
                {kids.length > 0 && <ul>{kids.map((k) => renderNode(k))}</ul>}
            </li>
        );
    };

    return (
        <section id="org" className="relative scroll-mt-24 overflow-hidden py-20 md:py-28">
            <TechBackdrop />
            <div className="relative mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-8 max-w-2xl">
                    <p className="text-sm font-bold tracking-[0.25em] text-accent">TỔ CHỨC</p>
                    <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">Sơ đồ tổ chức</h2>
                    <p className="mt-3 text-white/70">Cơ cấu Phòng Công Nghệ — bấm vào từng thành viên để xem hồ sơ chi tiết.</p>
                </div>

                {/* Chú thích nhánh */}
                <div className="mb-6 flex flex-wrap gap-x-5 gap-y-2">
                    {Object.entries(deptLabels).map(([key, label]) => (
                        <span key={key} className="inline-flex items-center gap-2 text-sm text-white/70">
                            <span className="h-3 w-3 rounded-full" style={{ background: deptColors[key] }} />
                            {label}
                        </span>
                    ))}
                </div>

                {/* Cây tổ chức — cuộn ngang trên màn hình nhỏ */}
                <div className="no-scrollbar overflow-x-auto pb-4">
                    <div className="org-tree mx-auto w-max px-2">
                        <ul>{renderNode(root.id)}</ul>
                    </div>
                </div>
            </div>

            <MemberDrawer
                member={selected}
                open={open}
                onOpenChange={(o) => {
                    setOpen(o);
                    if (!o) setTimeout(() => setSelected(null), 300);
                }}
            />
        </section>
    );
}
