import { useState } from 'react';
import * as Dialog from '@radix-ui/react-dialog';
import { AnimatePresence, motion } from 'framer-motion';
import { X } from 'lucide-react';
import { cultureItems } from '@/data/culture';
import type { CultureItem } from '@/types/tdxp';
import { Img } from '@/components/ui/Img';
import { cn } from '@/lib/utils';

const aspectCls: Record<NonNullable<CultureItem['aspect']>, string> = {
    tall: 'aspect-[3/4]',
    wide: 'aspect-[4/3]',
    square: 'aspect-square',
};

export function CultureGallery() {
    const [selected, setSelected] = useState<CultureItem | null>(null);

    return (
        <section id="culture" className="scroll-mt-24 py-20 md:py-28">
            <div className="mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-10 max-w-2xl">
                    <p className="text-sm font-bold tracking-[0.25em] text-accent">VĂN HOÁ</p>
                    <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">Văn hoá Phòng Công Nghệ</h2>
                    <p className="mt-3 text-white/70">
                        Học hỏi liên tục, phối hợp cởi mở và tạo ra giá trị thật trong từng hoạt động.
                    </p>
                </div>

                {/* Masonry bằng CSS columns — mỗi item tránh vỡ cột */}
                <div className="columns-2 gap-4 md:columns-3 [&>*]:mb-4">
                    {cultureItems.map((item, i) => (
                        <motion.button
                            key={item.id}
                            type="button"
                            onClick={() => setSelected(item)}
                            className="group relative block w-full break-inside-avoid overflow-hidden rounded-2xl border border-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-glow/50"
                            initial={{ opacity: 0, scale: 0.92 }}
                            whileInView={{ opacity: 1, scale: 1 }}
                            viewport={{ once: true, margin: '-40px' }}
                            transition={{ duration: 0.5, delay: Math.min(i * 0.05, 0.4), ease: [0.22, 1, 0.36, 1] }}
                        >
                            <Img
                                src={item.src}
                                alt={item.caption ?? 'Hoạt động Phòng Công Nghệ'}
                                wrapperClassName={cn('block w-full', aspectCls[item.aspect ?? 'square'])}
                                className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                            <span className="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent opacity-0 transition duration-300 group-hover:opacity-100" />
                            {item.caption && (
                                <span className="absolute inset-x-3 bottom-3 translate-y-2 text-left text-sm font-semibold text-white opacity-0 transition duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                                    {item.caption}
                                </span>
                            )}
                        </motion.button>
                    ))}
                </div>
                <p className="mt-6 text-xs text-white/35">
                    * Hình ảnh hiện mang tính minh hoạ — sẽ thay bằng ảnh hoạt động thực tế của Phòng.
                </p>
            </div>

            {/* Lightbox */}
            <Dialog.Root open={!!selected} onOpenChange={(o) => !o && setSelected(null)}>
                <AnimatePresence>
                    {selected && (
                        <Dialog.Portal forceMount>
                            <Dialog.Overlay asChild>
                                <motion.div
                                    className="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm"
                                    initial={{ opacity: 0 }}
                                    animate={{ opacity: 1 }}
                                    exit={{ opacity: 0 }}
                                />
                            </Dialog.Overlay>
                            <Dialog.Content asChild>
                                <motion.div
                                    className="fixed left-1/2 top-1/2 z-50 w-[92vw] max-w-3xl -translate-x-1/2 -translate-y-1/2"
                                    initial={{ opacity: 0, scale: 0.9 }}
                                    animate={{ opacity: 1, scale: 1 }}
                                    exit={{ opacity: 0, scale: 0.95 }}
                                    transition={{ type: 'spring', damping: 26, stiffness: 260 }}
                                >
                                    <Dialog.Title className="sr-only">{selected.caption ?? 'Hình ảnh hoạt động'}</Dialog.Title>
                                    <img
                                        src={selected.src}
                                        alt={selected.caption ?? ''}
                                        className="max-h-[80vh] w-full rounded-2xl object-contain shadow-2xl"
                                    />
                                    {selected.caption && (
                                        <p className="mt-3 text-center text-sm text-white/80">{selected.caption}</p>
                                    )}
                                    <Dialog.Close asChild>
                                        <button
                                            type="button"
                                            aria-label="Đóng"
                                            className="absolute -top-12 right-0 rounded-full bg-white/10 p-2 text-white transition hover:bg-white/20"
                                        >
                                            <X className="h-5 w-5" />
                                        </button>
                                    </Dialog.Close>
                                </motion.div>
                            </Dialog.Content>
                        </Dialog.Portal>
                    )}
                </AnimatePresence>
            </Dialog.Root>
        </section>
    );
}
