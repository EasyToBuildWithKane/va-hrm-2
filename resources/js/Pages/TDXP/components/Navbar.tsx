import { useEffect, useState } from 'react';
import { AnimatePresence, motion, useScroll, useSpring } from 'framer-motion';
import { Cpu, Menu, X } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/Button';

const navItems = [
    { id: 'hero', label: 'Trang chủ' },
    { id: 'about', label: 'Giới thiệu' },
    { id: 'impact', label: 'Thành tựu' },
    { id: 'products', label: 'Sản phẩm' },
    { id: 'stack', label: 'Công nghệ' },
    { id: 'team', label: 'Đội ngũ' },
    { id: 'projects', label: 'Dự án' },
    { id: 'ai', label: 'AI Lab' },
    { id: 'culture', label: 'Văn hoá' },
    { id: 'roadmap', label: 'Lộ trình' },
];

export function Navbar() {
    const [scrolled, setScrolled] = useState(false);
    const [menuOpen, setMenuOpen] = useState(false);
    const [active, setActive] = useState('hero');

    const { scrollYProgress } = useScroll();
    const progress = useSpring(scrollYProgress, { stiffness: 120, damping: 30, mass: 0.3 });

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 24);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    useEffect(() => {
        const observer = new IntersectionObserver(
            (entries) => entries.forEach((e) => e.isIntersecting && setActive(e.target.id)),
            { rootMargin: '-45% 0px -50% 0px' },
        );
        navItems.forEach(({ id }) => {
            const el = document.getElementById(id);
            if (el) observer.observe(el);
        });
        return () => observer.disconnect();
    }, []);

    const scrollTo = (id: string) => {
        document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
        setMenuOpen(false);
    };

    return (
        <header className={cn('fixed inset-x-0 top-0 z-40 transition-all duration-300', scrolled ? 'py-2' : 'py-4')}>
            {/* Thanh tiến độ cuộn — chi tiết "tech" */}
            <motion.div
                style={{ scaleX: progress }}
                className="absolute inset-x-0 top-0 h-0.5 origin-left bg-gradient-to-r from-primary via-glow to-accent"
            />

            <div className="mx-auto max-w-7xl px-4 md:px-8">
                <div
                    className={cn(
                        'flex items-center justify-between gap-4 rounded-2xl border px-3 py-2.5 transition-all duration-300 md:px-4',
                        scrolled
                            ? 'border-white/10 bg-secondary/70 shadow-lg shadow-black/20 backdrop-blur-xl'
                            : 'border-transparent bg-transparent',
                    )}
                >
                    {/* Thương hiệu */}
                    <button type="button" onClick={() => scrollTo('hero')} className="flex items-center gap-3">
                        <span className="relative flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-primary-deep text-white shadow-[0_0_18px_rgba(154,0,54,0.6)]">
                            <Cpu className="h-5 w-5" />
                            <span className="absolute -right-0.5 -top-0.5 h-2.5 w-2.5 animate-pulse rounded-full bg-emerald-400 ring-2 ring-secondary" />
                        </span>
                        <span className="text-left leading-tight">
                            <span className="block font-mono text-[10px] font-bold tracking-[0.3em] text-glow">TDXP</span>
                            <span className="block text-sm font-semibold text-white">Phòng Công Nghệ</span>
                        </span>
                    </button>

                    {/* Nav desktop — pill active trượt mượt */}
                    <nav className="hidden items-center gap-0.5 lg:flex" aria-label="Điều hướng chính">
                        {navItems.slice(1, 7).map((item) => {
                            const isActive = active === item.id;
                            return (
                                <button
                                    key={item.id}
                                    type="button"
                                    onClick={() => scrollTo(item.id)}
                                    aria-current={isActive ? 'true' : undefined}
                                    className="relative rounded-full px-3 py-2 text-sm transition"
                                >
                                    {isActive && (
                                        <motion.span
                                            layoutId="nav-active"
                                            className="absolute inset-0 -z-10 rounded-full bg-white/10 ring-1 ring-glow/30"
                                            transition={{ type: 'spring', stiffness: 400, damping: 32 }}
                                        />
                                    )}
                                    <span className={cn('transition', isActive ? 'font-medium text-white' : 'text-white/65 hover:text-white')}>
                                        {item.label}
                                    </span>
                                </button>
                            );
                        })}
                    </nav>

                    <div className="flex items-center gap-2">
                        <div className="hidden sm:block">
                            <Button size="sm" onClick={() => scrollTo('team')}>
                                Khám phá đội ngũ
                            </Button>
                        </div>
                        <button
                            type="button"
                            onClick={() => setMenuOpen((o) => !o)}
                            className="rounded-lg p-2 text-white transition hover:bg-white/10 lg:hidden"
                            aria-label={menuOpen ? 'Đóng menu' : 'Mở menu'}
                            aria-expanded={menuOpen}
                        >
                            {menuOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
                        </button>
                    </div>
                </div>

                {/* Menu mobile */}
                <AnimatePresence>
                    {menuOpen && (
                        <motion.nav
                            initial={{ opacity: 0, y: -8 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -8 }}
                            transition={{ duration: 0.2 }}
                            className="mt-2 rounded-2xl border border-white/10 bg-secondary/90 p-3 backdrop-blur-xl lg:hidden"
                            aria-label="Điều hướng di động"
                        >
                            <ul className="grid grid-cols-2 gap-1">
                                {navItems.map((item) => (
                                    <li key={item.id}>
                                        <button
                                            type="button"
                                            onClick={() => scrollTo(item.id)}
                                            aria-current={active === item.id ? 'true' : undefined}
                                            className={cn(
                                                'w-full rounded-lg px-3 py-2.5 text-left text-sm transition',
                                                active === item.id ? 'bg-white/10 font-medium text-white' : 'text-white/65 hover:bg-white/5 hover:text-white',
                                            )}
                                        >
                                            {item.label}
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </motion.nav>
                    )}
                </AnimatePresence>
            </div>
        </header>
    );
}
