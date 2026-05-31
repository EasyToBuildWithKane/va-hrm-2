import { useEffect, useState } from 'react';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/Button';
import { Menu, X } from 'lucide-react';

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

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 24);
        window.addEventListener('scroll', onScroll, { passive: true });
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    // Scroll-spy: đánh dấu section đang xem
    useEffect(() => {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) setActive(e.target.id);
                });
            },
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

    // Nền sáng khi cuộn / mở menu → chữ tối; ngược lại trong suốt trên nền navy → chữ sáng.
    const solid = scrolled || menuOpen;

    return (
        <header
            className={cn(
                'fixed top-0 z-40 w-full transition-all duration-300',
                solid
                    ? 'border-b border-secondary/10 bg-surface/90 shadow-sm backdrop-blur-md'
                    : 'bg-transparent',
            )}
        >
            <div className="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 md:px-8">
                <button type="button" onClick={() => scrollTo('hero')} className="text-left">
                    <span className={cn('block text-xs font-bold tracking-[0.2em]', solid ? 'text-primary' : 'text-accent')}>
                        TDXP
                    </span>
                    <span className={cn('text-sm font-semibold', solid ? 'text-secondary' : 'text-white')}>Phòng Công Nghệ</span>
                </button>

                <nav className="hidden items-center gap-1 lg:flex" aria-label="Điều hướng chính">
                    {navItems.slice(1, 6).map((item) => (
                        <button
                            key={item.id}
                            type="button"
                            onClick={() => scrollTo(item.id)}
                            aria-current={active === item.id ? 'true' : undefined}
                            className={cn(
                                'rounded-full px-3 py-2 text-sm transition',
                                active === item.id
                                    ? solid
                                        ? 'bg-primary/10 font-medium text-primary'
                                        : 'bg-white/15 font-medium text-white'
                                    : solid
                                      ? 'text-secondary/70 hover:bg-secondary/5 hover:text-secondary'
                                      : 'text-white/80 hover:bg-white/10 hover:text-white',
                            )}
                        >
                            {item.label}
                        </button>
                    ))}
                </nav>

                <div className="flex items-center gap-2">
                    <div className="hidden sm:block">
                        <Button size="sm" variant="outline" onClick={() => scrollTo('team')}>
                            Khám phá đội ngũ
                        </Button>
                    </div>
                    <button
                        type="button"
                        onClick={() => setMenuOpen((o) => !o)}
                        className={cn(
                        'rounded-lg p-2 lg:hidden',
                        solid ? 'text-secondary hover:bg-secondary/5' : 'text-white hover:bg-white/10',
                    )}
                        aria-label={menuOpen ? 'Đóng menu' : 'Mở menu'}
                        aria-expanded={menuOpen}
                    >
                        {menuOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
                    </button>
                </div>
            </div>

            {/* Menu mobile */}
            {menuOpen && (
                <nav
                    className="border-t border-secondary/10 bg-surface/95 px-4 py-3 backdrop-blur-md lg:hidden"
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
                                        active === item.id
                                            ? 'bg-primary/10 font-medium text-primary'
                                            : 'text-secondary/70 hover:bg-secondary/5',
                                    )}
                                >
                                    {item.label}
                                </button>
                            </li>
                        ))}
                    </ul>
                </nav>
            )}
        </header>
    );
}
