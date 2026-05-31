import { motion } from 'framer-motion';
import type { TeamMember } from '@/types/tdxp';
import { Badge } from '@/components/ui/Badge';
import { cn } from '@/lib/utils';

interface MemberCardProps {
    member: TeamMember;
    onSelect: (member: TeamMember) => void;
    layoutIdPrefix?: string;
}

export function MemberCard({ member, onSelect, layoutIdPrefix = 'member' }: MemberCardProps) {
    return (
        <motion.button
            type="button"
            onClick={() => onSelect(member)}
            className={cn(
                'group light-beam relative w-full overflow-hidden rounded-2xl border border-white/10 bg-white/[0.05] text-left backdrop-blur-md',
                'shadow-sm transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-glow/50',
            )}
            whileHover={{ y: -6, scale: 1.01 }}
            transition={{ type: 'spring', stiffness: 400, damping: 28 }}
        >
            <div className="absolute inset-0 bg-gradient-to-br from-white/0 to-white/10 opacity-0 transition group-hover:opacity-100" />
            <div className="relative p-6">
                <motion.div
                    layoutId={`${layoutIdPrefix}-avatar-${member.id}`}
                    className="mx-auto flex h-24 w-24 items-end justify-center overflow-hidden rounded-2xl shadow-lg ring-2 ring-white transition group-hover:scale-105 group-hover:shadow-primary/20"
                    style={{
                        background:
                            'radial-gradient(120% 120% at 50% 0%, #c3004a 0%, #9a0036 55%, #6d0026 100%)',
                    }}
                >
                    <img
                        src={member.avatar}
                        alt={`Ảnh đại diện ${member.name}`}
                        loading="lazy"
                        decoding="async"
                        className="h-[120%] w-auto object-contain object-bottom drop-shadow-[0_6px_10px_rgba(0,0,0,0.25)]"
                    />
                </motion.div>
                <h3 className="mt-4 text-center text-lg font-bold text-white">{member.name}</h3>
                <p className="text-center text-sm text-glow">{member.role}</p>
                <div className="mt-4 flex flex-wrap justify-center gap-2">
                    {member.expertiseTags.slice(0, 3).map((tag) => (
                        <Badge key={tag} variant="outline">
                            {tag}
                        </Badge>
                    ))}
                </div>
                <p className="mt-4 text-center text-xs text-white/50">
                    {member.activeProjectCount} dự án đang triển khai
                </p>
                <blockquote className="mt-4 line-clamp-3 text-center text-sm italic text-white/65 opacity-0 transition group-hover:opacity-100">
                    &ldquo;{member.quote}&rdquo;
                </blockquote>
            </div>
            <div className="pointer-events-none absolute inset-0 rounded-2xl ring-0 ring-glow/0 transition group-hover:ring-2 group-hover:ring-glow/30" />
        </motion.button>
    );
}
