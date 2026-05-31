import { Mascot } from './Mascot';
import { site } from '@/data/site';
import { getLucideIcon } from '@/lib/lucide';

export function Footer() {
    const { contact, socials } = site;

    return (
        <footer id="footer" className="relative overflow-hidden bg-ink text-white">
            <div
                className="pointer-events-none absolute inset-0 opacity-90"
                style={{
                    background:
                        'radial-gradient(60% 80% at 85% 0%, rgba(36,99,156,0.32), transparent 60%), radial-gradient(50% 70% at 10% 100%, rgba(16,42,67,0.45), transparent 60%)',
                }}
            />
            <div className="relative mx-auto max-w-7xl px-4 py-16 md:px-8">
                <div className="grid gap-10 md:grid-cols-3 md:items-start">
                    {/* Thương hiệu */}
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
                            Kiến tạo nền tảng số cho giáo dục tương lai.
                        </p>
                    </div>

                    {/* Liên hệ */}
                    <div className="text-center md:text-left">
                        <h3 className="text-sm font-bold tracking-[0.2em] text-white/50">LIÊN HỆ</h3>
                        <ul className="mt-4 space-y-2 text-sm text-white/75">
                            <li>{contact.org}</li>
                            <li>
                                <a href={`mailto:${contact.email}`} className="transition hover:text-glow">
                                    {contact.email}
                                </a>
                            </li>
                            {contact.phone && <li>{contact.phone}</li>}
                            {contact.address && <li>{contact.address}</li>}
                        </ul>
                    </div>

                    {/* Social + mascot */}
                    <div className="flex flex-col items-center gap-8 md:items-end">
                        <div>
                            <h3 className="mb-4 text-center text-sm font-bold tracking-[0.2em] text-white/50 md:text-right">
                                KẾT NỐI
                            </h3>
                            <div className="flex justify-center gap-3 md:justify-end">
                                {socials.map((s) => {
                                    const Icon = getLucideIcon(s.icon);
                                    return (
                                        <a
                                            key={s.id}
                                            href={s.href}
                                            aria-label={s.label}
                                            target="_blank"
                                            rel="noreferrer"
                                            className="flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white/70 transition-all duration-300 hover:scale-110 hover:border-glow/60 hover:bg-white/10 hover:text-glow hover:shadow-[0_0_20px_rgba(255,92,138,0.5)]"
                                        >
                                            <Icon className="h-5 w-5" />
                                        </a>
                                    );
                                })}
                            </div>
                        </div>
                        <Mascot pose="stand" size={140} float className="shrink-0" />
                    </div>
                </div>

                <div className="mt-12 border-t border-white/10 pt-6 text-center text-xs text-white/45">
                    © {new Date().getFullYear()} VA Schools · Phòng Công Nghệ. Xây dựng cho trình diễn &amp; truyền thông nội bộ.
                </div>
            </div>
        </footer>
    );
}
