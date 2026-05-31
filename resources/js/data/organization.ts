import type { Node, Edge } from '@xyflow/react';
import { getMemberById } from './team';

export type OrgKind = 'root' | 'department' | 'employee' | 'project';

export interface OrgNodeData extends Record<string, unknown> {
    label: string;
    subtitle?: string;
    headcount?: number;
    kind: OrgKind;
    dept: string;
    avatar?: string;
    progress?: number;
}

/** Màu theo nhánh — dùng cho node, edge highlight & legend. */
export const deptColors: Record<string, string> = {
    core: '#9A0036',
    software: '#C3004A',
    hardware: '#102A43',
    system: '#6D0026',
};

export const deptLabels: Record<string, string> = {
    core: 'Ban Công nghệ',
    software: 'Phần Mềm',
    hardware: 'Phần Cứng',
    system: 'System',
};

/** Node nhân sự — lấy tên/chức danh từ team.ts. Dùng icon theo kind (không dùng mascot ở kích thước nhỏ). */
function emp(id: string, dept: string, kind: OrgKind = 'employee', headcount?: number): Node<OrgNodeData> {
    const m = getMemberById(id);
    return {
        id: `emp-${id}`,
        type: 'orgNode',
        position: { x: 0, y: 0 },
        data: {
            label: m?.name ?? id,
            subtitle: m?.role,
            kind,
            dept,
            headcount,
        },
    };
}

export const orgNodes: Node<OrgNodeData>[] = [
    // L1 — Giám đốc Công nghệ kiêm Trưởng phòng
    emp('toan', 'core', 'root', 13),

    // L2 — Phó phòng & Trưởng Ban CNTT (ngang hàng)
    emp('hoang', 'core'),
    emp('hung', 'core'),

    // L3 — Leader Phần Mềm · Leader Phần Cứng · System (ngang hàng)
    emp('khoa', 'software'),
    emp('thai', 'hardware'),
    emp('truong', 'system'),

    // L4 — Team Phần Mềm
    emp('kieu', 'software'),
    emp('hoa', 'software'),
    emp('truc', 'software'),
    emp('binh', 'software'),
    emp('quang', 'software'),

    // L4 — Team Phần Cứng
    emp('loc', 'hardware'),
    emp('thang', 'hardware'),
    emp('vu', 'hardware'),
];

function edge(source: string, target: string, dept: string): Edge {
    return {
        id: `${source}-${target}`,
        source,
        target,
        style: { stroke: deptColors[dept] ?? '#9A0036', strokeWidth: 1.5 },
    };
}

export const orgEdges: Edge[] = [
    // L1 → L2
    edge('emp-toan', 'emp-hoang', 'core'),
    edge('emp-toan', 'emp-hung', 'core'),

    // L2 (Trưởng Ban CNTT) → L3
    edge('emp-hung', 'emp-khoa', 'software'),
    edge('emp-hung', 'emp-thai', 'hardware'),
    edge('emp-hung', 'emp-truong', 'system'),

    // L3 (Leader Phần Mềm) → thành viên
    edge('emp-khoa', 'emp-kieu', 'software'),
    edge('emp-khoa', 'emp-hoa', 'software'),
    edge('emp-khoa', 'emp-truc', 'software'),
    edge('emp-khoa', 'emp-binh', 'software'),
    edge('emp-khoa', 'emp-quang', 'software'),

    // L3 (Leader Phần Cứng) → thành viên
    edge('emp-thai', 'emp-loc', 'hardware'),
    edge('emp-thai', 'emp-thang', 'hardware'),
    edge('emp-thai', 'emp-vu', 'hardware'),
];
