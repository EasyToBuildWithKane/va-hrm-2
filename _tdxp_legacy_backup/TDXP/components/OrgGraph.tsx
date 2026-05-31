import { memo, useCallback, useMemo, useState } from 'react';
import {
    ReactFlow,
    Background,
    Controls,
    MiniMap,
    type Node,
    type Edge,
    type NodeProps,
    Handle,
    Position,
} from '@xyflow/react';
import '@xyflow/react/dist/style.css';
import dagre from 'dagre';
import { motion } from 'framer-motion';
import { orgEdges, orgNodes, deptColors, deptLabels, type OrgNodeData } from '@/data/organization';
import { cn } from '@/lib/utils';
import { Building2, Users, FolderKanban, Crown, Search, ChevronDown, ChevronRight } from 'lucide-react';

const nodeWidth = 210;
const nodeHeight = 76;

const kindIcon = { root: Crown, department: Building2, employee: Users, project: FolderKanban } as const;

function layoutElements(nodes: Node<OrgNodeData>[], edges: Edge[]) {
    const g = new dagre.graphlib.Graph();
    g.setDefaultEdgeLabel(() => ({}));
    g.setGraph({ rankdir: 'TB', nodesep: 36, ranksep: 72 });
    nodes.forEach((node) => g.setNode(node.id, { width: nodeWidth, height: nodeHeight }));
    edges.forEach((edge) => g.setEdge(edge.source, edge.target));
    dagre.layout(g);
    return nodes.map((node) => {
        const pos = g.node(node.id);
        return { ...node, position: { x: pos.x - nodeWidth / 2, y: pos.y - nodeHeight / 2 } };
    });
}

function OrgNodeComponent({ data }: NodeProps<Node<OrgNodeData>>) {
    const color = deptColors[data.dept] ?? '#9A0036';
    const Icon = kindIcon[data.kind];
    const dimmed = data.dimmed === true;
    const focused = data.focused === true;

    return (
        <motion.div
            initial={{ opacity: 0, scale: 0.9 }}
            animate={{ opacity: dimmed ? 0.25 : 1, scale: 1 }}
            transition={{ duration: 0.25 }}
            style={{ width: nodeWidth, boxShadow: focused ? `0 0 0 2px ${color}, 0 8px 24px ${color}33` : undefined }}
            className={cn(
                'rounded-xl border bg-white px-3 py-2.5 shadow-md transition',
                data.kind === 'root' ? 'border-primary/40' : 'border-secondary/10',
            )}
        >
            {data.kind !== 'root' && <Handle type="target" position={Position.Top} className="!h-1.5 !w-1.5 !border-0" style={{ background: color }} />}
            <div className="flex items-center gap-2.5">
                {data.avatar ? (
                    <img src={data.avatar} alt="" loading="lazy" decoding="async" width={36} height={36} className="h-9 w-9 shrink-0 rounded-lg object-cover" />
                ) : (
                    <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg" style={{ background: `${color}1a`, color }}>
                        <Icon className="h-4.5 w-4.5" strokeWidth={2} />
                    </span>
                )}
                <div className="min-w-0 flex-1">
                    <p className="truncate text-sm font-semibold text-secondary">{data.label}</p>
                    {data.subtitle && <p className="truncate text-[11px] text-secondary/55">{data.subtitle}</p>}
                </div>
                {data.headcount != null && (
                    <span className="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-medium" style={{ background: `${color}1a`, color }}>
                        {data.headcount}
                    </span>
                )}
            </div>
            {data.kind === 'project' && data.progress != null && (
                <div className="mt-2 h-1 overflow-hidden rounded-full bg-secondary/10">
                    <div className="h-full rounded-full" style={{ width: `${data.progress}%`, background: color }} />
                </div>
            )}
            <Handle type="source" position={Position.Bottom} className="!h-1.5 !w-1.5 !border-0" style={{ background: color }} />
        </motion.div>
    );
}

const nodeTypes = { orgNode: OrgNodeComponent };

function OrgGraphDesktop() {
    const [query, setQuery] = useState('');
    const [activeId, setActiveId] = useState<string | null>(null);

    const laidOut = useMemo(() => layoutElements(orgNodes, orgEdges), []);

    // Bản đồ quan hệ cha/con để truy vết tuyến báo cáo
    const { parentOf, childrenOf } = useMemo(() => {
        const parentOf = new Map<string, string>();
        const childrenOf = new Map<string, string[]>();
        orgEdges.forEach((e) => {
            parentOf.set(e.target, e.source);
            childrenOf.set(e.source, [...(childrenOf.get(e.source) ?? []), e.target]);
        });
        return { parentOf, childrenOf };
    }, []);

    const highlightSet = useMemo(() => {
        if (!activeId) return null;
        const set = new Set<string>([activeId]);
        let cur: string | undefined = activeId;
        while (cur && parentOf.has(cur)) {
            cur = parentOf.get(cur);
            if (cur) set.add(cur);
        }
        (childrenOf.get(activeId) ?? []).forEach((c) => set.add(c));
        return set;
    }, [activeId, parentOf, childrenOf]);

    const matchSet = useMemo(() => {
        const q = query.trim().toLowerCase();
        if (!q) return null;
        return new Set(
            orgNodes
                .filter((n) => `${n.data.label} ${n.data.subtitle ?? ''}`.toLowerCase().includes(q))
                .map((n) => n.id),
        );
    }, [query]);

    const isBright = useCallback(
        (id: string) => {
            if (highlightSet) return highlightSet.has(id);
            if (matchSet) return matchSet.has(id);
            return true;
        },
        [highlightSet, matchSet],
    );

    const nodes = useMemo(
        () =>
            laidOut.map((n) => ({
                ...n,
                draggable: false,
                data: { ...n.data, dimmed: !isBright(n.id), focused: n.id === activeId },
            })),
        [laidOut, isBright, activeId],
    );

    const edges = useMemo(
        () =>
            orgEdges.map((e) => {
                const onReportingLine = highlightSet?.has(e.target) && highlightSet?.has(e.source);
                const bright = highlightSet
                    ? onReportingLine
                    : matchSet
                      ? matchSet.has(e.source) && matchSet.has(e.target)
                      : true;
                return {
                    ...e,
                    animated: !!onReportingLine,
                    style: {
                        ...e.style,
                        strokeWidth: onReportingLine ? 2.5 : 1.5,
                        opacity: bright ? 1 : 0.15,
                    },
                };
            }),
        [highlightSet, matchSet],
    );

    return (
        <div>
            <div className="mx-auto mb-5 flex max-w-3xl flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <div className="relative w-full sm:max-w-xs">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-white/40" />
                    <input
                        type="search"
                        value={query}
                        onChange={(e) => setQuery(e.target.value)}
                        placeholder="Tìm phòng ban, thành viên, dự án…"
                        aria-label="Tìm trong sơ đồ tổ chức"
                        className="w-full rounded-full border border-white/15 bg-white/5 py-2 pl-9 pr-4 text-sm text-white outline-none backdrop-blur-sm transition placeholder:text-white/40 focus:border-glow/50 focus:ring-2 focus:ring-glow/20"
                    />
                </div>
                {/* Legend */}
                <ul className="flex flex-wrap items-center justify-center gap-x-4 gap-y-1.5">
                    {Object.entries(deptLabels).map(([key, label]) => (
                        <li key={key} className="flex items-center gap-1.5 text-xs text-white/70">
                            <span className="h-2.5 w-2.5 rounded-full ring-2 ring-white/10" style={{ background: deptColors[key] }} />
                            {label}
                        </li>
                    ))}
                </ul>
            </div>
            <div className="glass-card h-[460px] w-full overflow-hidden rounded-2xl shadow-[inset_0_2px_30px_rgba(0,0,0,0.25)] md:h-[520px]">
                <ReactFlow
                    nodes={nodes}
                    edges={edges}
                    nodeTypes={nodeTypes}
                    fitView
                    minZoom={0.3}
                    maxZoom={1.5}
                    nodesConnectable={false}
                    onNodeMouseEnter={(_, n) => setActiveId(n.id)}
                    onNodeMouseLeave={() => setActiveId(null)}
                    onNodeClick={(_, n) => setActiveId((cur) => (cur === n.id ? null : n.id))}
                    onPaneClick={() => setActiveId(null)}
                    proOptions={{ hideAttribution: true }}
                >
                    <Background gap={16} color="rgba(255,255,255,0.12)" />
                    <Controls showInteractive={false} />
                    <MiniMap
                        nodeColor={(n) => deptColors[(n.data as OrgNodeData)?.dept] ?? '#9A0036'}
                        maskColor="rgba(0,0,0,0.45)"
                        className="!rounded-lg !bg-white/10 !backdrop-blur"
                    />
                </ReactFlow>
            </div>
        </div>
    );
}

/** Các nhánh cấp Leader/System để hiển thị dạng accordion trên mobile. */
const mobileBranches: { id: string; label: string; dept: string }[] = [
    { id: 'emp-khoa', label: 'Phần Mềm — Nguyễn Anh Khoa', dept: 'software' },
    { id: 'emp-thai', label: 'Phần Cứng — Phạm Quang Thái', dept: 'hardware' },
    { id: 'emp-truong', label: 'System — Nguyễn Xuân Trường', dept: 'system' },
];

function OrgGraphMobile() {
    const [open, setOpen] = useState<string | null>('emp-khoa');

    const childrenOf = useMemo(() => {
        const map = new Map<string, string[]>();
        orgEdges.forEach((e) => map.set(e.source, [...(map.get(e.source) ?? []), e.target]));
        return map;
    }, []);

    const labelOf = (id: string) => orgNodes.find((n) => n.id === id)?.data;

    return (
        <div className="glass-card space-y-2 rounded-2xl p-4 md:hidden">
            <div className="mb-1 flex items-center gap-2.5 rounded-lg bg-white/10 px-4 py-3">
                <Crown className="h-4 w-4 text-glow" />
                <span className="font-semibold text-white">Bùi Quang Toàn · Giám đốc Công nghệ</span>
            </div>
            <div className="mb-2 ml-6 flex flex-col gap-1.5 border-l-2 border-white/20 pl-3 text-sm text-white/80">
                <span className="flex items-center gap-2">
                    <Users className="h-3.5 w-3.5 text-white/50" /> Bùi Huy Hoàng · Phó phòng Công nghệ
                </span>
                <span className="flex items-center gap-2">
                    <Users className="h-3.5 w-3.5 text-white/50" /> Nguyễn Viết Hùng · Trưởng Ban CNTT
                </span>
            </div>
            {mobileBranches.map((b) => {
                const color = deptColors[b.dept];
                const children = (childrenOf.get(b.id) ?? []).map(labelOf).filter(Boolean);
                const isOpen = open === b.id;
                const hasChildren = children.length > 0;
                return (
                    <div key={b.id} className="border-l-2 pl-3" style={{ borderColor: `${color}55` }}>
                        <button
                            type="button"
                            className="flex w-full items-center gap-2 rounded-lg px-2 py-2.5 text-left font-semibold text-white"
                            onClick={() => hasChildren && setOpen((o) => (o === b.id ? null : b.id))}
                            aria-expanded={isOpen}
                        >
                            {hasChildren ? (
                                isOpen ? (
                                    <ChevronDown className="h-4 w-4" />
                                ) : (
                                    <ChevronRight className="h-4 w-4" />
                                )
                            ) : (
                                <span className="h-4 w-4" />
                            )}
                            <span className="h-2.5 w-2.5 rounded-full" style={{ background: color }} />
                            {b.label}
                            {hasChildren && (
                                <span className="ml-auto text-xs text-white/50">{children.length} người</span>
                            )}
                        </button>
                        {isOpen && hasChildren && (
                            <ul className="ml-6 space-y-1.5 pb-2">
                                {children.map((c) => (
                                    <li key={c!.label} className="flex items-center gap-2 text-sm text-white/80">
                                        <Users className="h-3.5 w-3.5 text-white/40" />
                                        <span>{c!.label}</span>
                                        <span className="ml-auto text-xs text-white/45">{c!.subtitle}</span>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>
                );
            })}
        </div>
    );
}

export const OrgGraph = memo(function OrgGraph() {
    return (
        <section id="org-inner" className="py-20 md:py-28">
            <div className="mx-auto max-w-7xl px-4 md:px-8">
                <div className="mx-auto mb-12 max-w-2xl text-center">
                    <p className="text-sm font-bold tracking-[0.25em] text-glow">SƠ ĐỒ TỔ CHỨC</p>
                    <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">Cơ cấu tổ chức</h2>
                    <p className="mx-auto mt-3 max-w-xl text-white/65">
                        Di chuột vào một node để làm nổi tuyến báo cáo · tìm kiếm để lọc phòng ban, thành viên, dự án.
                    </p>
                </div>
                <div className="hidden md:block">
                    <OrgGraphDesktop />
                </div>
                <OrgGraphMobile />
            </div>
        </section>
    );
});
