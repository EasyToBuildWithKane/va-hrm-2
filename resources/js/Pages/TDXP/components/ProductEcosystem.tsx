import { useState } from 'react';
import { motion } from 'framer-motion';
import { PlayCircle, ArrowUpRight } from 'lucide-react';
import { products } from '@/data/products';
import type { Product, ProductStatus } from '@/types/tdxp';
import { getLucideIcon } from '@/lib/lucide';
import { Img } from '@/components/ui/Img';
import { Badge } from '@/components/ui/Badge';
import { Drawer } from '@/components/ui/Drawer';
import { Slider } from './Slider';
import { TechBackdrop } from './TechBackdrop';
import { cn } from '@/lib/utils';

const statusMeta: Record<ProductStatus, { label: string; cls: string }> = {
    live: { label: 'Đang vận hành', cls: 'bg-emerald-400/15 text-emerald-300' },
    beta: { label: 'Thử nghiệm', cls: 'bg-amber-400/15 text-amber-300' },
    development: { label: 'Đang phát triển', cls: 'bg-sky-400/15 text-sky-300' },
    planned: { label: 'Sắp ra mắt', cls: 'bg-white/10 text-white/55' },
};

function StatusBadge({ status }: { status: ProductStatus }) {
    const meta = statusMeta[status];
    return (
        <span className={cn('inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold backdrop-blur-sm', meta.cls)}>
            {meta.label}
        </span>
    );
}

function ProductCard({ product, onOpen }: { product: Product; onOpen: () => void }) {
    const Icon = getLucideIcon(product.icon);
    const placeholder = product.confirmed === false;

    return (
        <motion.button
            type="button"
            onClick={onOpen}
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, ease: [0.22, 1, 0.36, 1] }}
            whileHover={{ y: -6 }}
            className="group flex h-full w-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-white/[0.05] text-left backdrop-blur-md transition hover:border-white/25 hover:shadow-[0_0_40px_rgba(255,92,138,0.22)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-glow/50"
        >
            <div className="relative h-44 overflow-hidden">
                {product.image && (
                    <Img
                        src={product.image}
                        alt={product.name}
                        wrapperClassName="absolute inset-0 h-full w-full"
                        className={cn(
                            'h-full w-full object-cover transition-transform duration-500 group-hover:scale-110',
                            placeholder ? 'opacity-40 grayscale' : 'opacity-70',
                        )}
                    />
                )}
                <div className="absolute inset-0 bg-gradient-to-t from-secondary via-secondary/40 to-transparent" />
                <div className="absolute inset-x-4 top-4 flex items-start justify-between">
                    <span className="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-glow backdrop-blur-sm">
                        <Icon className="h-5 w-5" />
                    </span>
                    <StatusBadge status={product.status} />
                </div>
            </div>

            <div className="flex flex-1 flex-col p-6">
                <h3 className="text-xl font-bold text-white">{product.name}</h3>
                <p className="mt-1 text-sm font-medium text-glow">{product.tagline}</p>
                <p className="mt-3 line-clamp-3 text-sm leading-relaxed text-white/65">{product.description}</p>
                <div className="mt-4 flex flex-wrap gap-2">
                    {product.technologies.slice(0, 4).map((t) => (
                        <Badge key={t} variant="outline">
                            {t}
                        </Badge>
                    ))}
                </div>
                <span className="mt-auto inline-flex items-center gap-1 pt-5 text-xs font-semibold text-white/60 transition group-hover:text-white">
                    {product.video ? <PlayCircle className="h-4 w-4" /> : <ArrowUpRight className="h-4 w-4" />}
                    {product.video ? 'Xem demo' : 'Chi tiết'}
                </span>
            </div>
        </motion.button>
    );
}

export function ProductEcosystem() {
    const [selected, setSelected] = useState<Product | null>(null);

    return (
        <section id="products" className="relative scroll-mt-24 overflow-hidden py-20 md:py-28">
            <TechBackdrop />
            <div className="relative mx-auto max-w-7xl px-4 md:px-8">
                <div className="mb-10 max-w-2xl">
                    <p className="text-sm font-bold tracking-[0.25em] text-accent">SẢN PHẨM</p>
                    <h2 className="mt-3 text-3xl font-bold text-white md:text-4xl">Hệ sinh thái sản phẩm</h2>
                    <p className="mt-3 text-white/70">
                        Bộ giải pháp số phục vụ vận hành toàn hệ thống — từ nhân sự, vận hành đến trợ lý AI.
                    </p>
                </div>

                <Slider slideClassName="w-[85%] sm:w-[360px]" ariaLabel="Danh sách sản phẩm">
                    {products.map((p) => (
                        <ProductCard key={p.id} product={p} onOpen={() => setSelected(p)} />
                    ))}
                </Slider>
            </div>

            <Drawer open={!!selected} onOpenChange={(o) => !o && setSelected(null)} title={selected?.name}>
                {selected && (
                    <div className="p-6 md:p-8">
                        <div className="relative aspect-video overflow-hidden rounded-2xl border border-white/10">
                            {selected.video ? (
                                <video src={selected.video} controls poster={selected.image} className="h-full w-full object-cover">
                                    <track kind="captions" />
                                </video>
                            ) : (
                                <>
                                    {selected.image && (
                                        <Img
                                            src={selected.image}
                                            alt={selected.name}
                                            wrapperClassName="absolute inset-0 h-full w-full"
                                            className="h-full w-full object-cover opacity-60"
                                        />
                                    )}
                                    <div className="absolute inset-0 flex items-center justify-center bg-secondary/60 text-center">
                                        <p className="px-6 text-sm text-white/70">
                                            <PlayCircle className="mx-auto mb-2 h-8 w-8 text-white/50" />
                                            Video demo sẽ được cập nhật.
                                        </p>
                                    </div>
                                </>
                            )}
                        </div>
                        <div className="mt-5 flex items-center gap-3">
                            <StatusBadge status={selected.status} />
                            <p className="text-sm font-medium text-glow">{selected.tagline}</p>
                        </div>
                        <p className="mt-3 leading-relaxed text-white/75">{selected.description}</p>
                        <div className="mt-5 flex flex-wrap gap-2">
                            {selected.technologies.map((t) => (
                                <Badge key={t} variant="outline">
                                    {t}
                                </Badge>
                            ))}
                        </div>
                        {selected.confirmed === false && (
                            <p className="mt-5 rounded-xl border border-amber-400/20 bg-amber-400/10 px-4 py-3 text-sm text-amber-200/90">
                                Đây là định hướng sản phẩm — nội dung cần Phòng Công Nghệ xác nhận trước khi công bố.
                            </p>
                        )}
                        {selected.href && (
                            <a
                                href={selected.href}
                                className="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-glow hover:text-white"
                            >
                                Truy cập sản phẩm <ArrowUpRight className="h-4 w-4" />
                            </a>
                        )}
                    </div>
                )}
            </Drawer>
        </section>
    );
}
