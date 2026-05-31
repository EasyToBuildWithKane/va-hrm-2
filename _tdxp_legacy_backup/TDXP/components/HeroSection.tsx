import { motion } from 'framer-motion';
import { Button } from '@/components/ui/Button';
import { TechOrbit } from './TechOrbit';

const container = {
    hidden: { opacity: 0 },
    show: {
        opacity: 1,
        transition: { staggerChildren: 0.12 },
    },
};

const item = {
    hidden: { opacity: 0, y: 24 },
    show: { opacity: 1, y: 0, transition: { duration: 0.6, ease: [0.22, 1, 0.36, 1] } },
};

export function HeroSection() {
    const scrollTo = (id: string) => {
        document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
    };

    return (
        <section
            id="hero"
            className="bg-aurora bg-mesh relative flex min-h-screen flex-col justify-center overflow-hidden pt-24 pb-16 md:pt-28"
        >
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(36,99,156,0.10),transparent_50%)]" />
            <div className="mx-auto grid max-w-7xl flex-1 items-center gap-12 px-4 md:grid-cols-2 md:gap-8 md:px-8 lg:gap-16">
                <motion.div variants={container} initial="hidden" animate="show" className="max-w-xl">
                    <motion.p variants={item} className="text-sm font-bold tracking-[0.25em] text-accent">
                        PHÒNG CÔNG NGHỆ
                    </motion.p>
                    <motion.h1
                        variants={item}
                        className="mt-4 text-4xl font-bold tracking-tight text-white md:text-5xl lg:text-6xl"
                    >
                        Công nghệ dẫn dắt tăng trưởng
                    </motion.h1>
                    <motion.p variants={item} className="mt-6 text-lg leading-relaxed text-white/70">
                        Xây dựng nền tảng số.
                        <br />
                        Tự động hóa vận hành.
                        <br />
                        Ứng dụng AI vào thực tiễn.
                    </motion.p>
                    <motion.div variants={item} className="mt-10 flex flex-wrap gap-4">
                        <Button size="lg" onClick={() => scrollTo('team')}>
                            Khám phá đội ngũ
                        </Button>
                        <Button size="lg" variant="outline" onClick={() => scrollTo('projects')}>
                            Xem dự án
                        </Button>
                    </motion.div>
                </motion.div>
                <motion.div
                    initial={{ opacity: 0, scale: 0.92 }}
                    animate={{ opacity: 1, scale: 1 }}
                    transition={{ duration: 0.8, delay: 0.2 }}
                    className="relative flex justify-center"
                >
                    <TechOrbit />
                </motion.div>
            </div>
        </section>
    );
}
