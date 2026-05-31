import { Mascot } from './Mascot';

export function Footer() {
    return (
        <footer className="relative overflow-hidden bg-ink text-white">
            <div
                className="pointer-events-none absolute inset-0 opacity-90"
                style={{
                    background:
                        'radial-gradient(60% 80% at 85% 0%, rgba(36,99,156,0.32), transparent 60%), radial-gradient(50% 70% at 10% 100%, rgba(16,42,67,0.45), transparent 60%)',
                }}
            />
            <div className="relative mx-auto flex max-w-7xl flex-col items-center gap-10 px-4 py-16 md:flex-row md:items-end md:justify-between md:px-8">
                <div className="text-center md:text-left">
                    <img
                        src="/assets/brandva/logofooter.png"
                        alt="VA Schools"
                        className="mx-auto h-12 w-auto md:mx-0"
                        loading="lazy"
                        decoding="async"
                    />
                    <p className="mt-4 text-lg font-bold tracking-wide text-white">Phòng Công Nghệ</p>
                    <p className="mt-1 max-w-sm text-sm text-white/70">
                        Technology Experience Portal — Công nghệ dẫn dắt tăng trưởng.
                    </p>
                    <p className="mt-4 text-xs text-white/45">
                        © {new Date().getFullYear()} VA Schools · Phòng Công Nghệ. Xây dựng cho trình diễn &amp; truyền thông nội bộ.
                    </p>
                </div>
                <Mascot pose="stand" size={180} float className="shrink-0" />
            </div>
        </footer>
    );
}
