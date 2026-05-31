import { Head } from '@inertiajs/react';
import { Suspense, lazy } from 'react';
import { useInView } from 'react-intersection-observer';
import { Navbar } from './components/Navbar';
import { HeroSection } from './components/HeroSection';
import { AboutSection } from './components/AboutSection';
import { ImpactMetrics } from './components/ImpactMetrics';
import { ProductEcosystem } from './components/ProductEcosystem';
import { TechStack } from './components/TechStack';
import { TeamSection } from './components/TeamSection';
import { ProjectTimeline } from './components/ProjectTimeline';
import { AILab } from './components/AILab';
import { CultureGallery } from './components/CultureGallery';
import { Roadmap } from './components/Roadmap';
import { Footer } from './components/Footer';
import { ToastProvider, useWelcomeToast } from '@/components/ui/Toast';
import { Skeleton } from '@/components/ui/Skeleton';

// Lazy: cô lập bundle nặng (React Flow ~275kB) cho sơ đồ tổ chức khỏi initial load.
const OrgGraph = lazy(() => import('./components/OrgGraph').then((m) => ({ default: m.OrgGraph })));

function SectionFallback() {
    return (
        <div className="mx-auto max-w-7xl px-4 py-20 md:px-8 md:py-28">
            <Skeleton className="h-8 w-64" />
            <Skeleton className="mt-3 h-4 w-96 max-w-full" />
            <Skeleton className="mt-10 h-[420px] w-full rounded-2xl" />
        </div>
    );
}

/** Chỉ mount (và tải chunk) khi section sắp vào viewport. Anchor id nằm trên wrapper. */
function DeferredSection({ id, children }: { id: string; children: React.ReactNode }) {
    const { ref, inView } = useInView({ triggerOnce: true, rootMargin: '500px 0px' });
    return (
        <div id={id} ref={ref} className="scroll-mt-24">
            {inView ? <Suspense fallback={<SectionFallback />}>{children}</Suspense> : <SectionFallback />}
        </div>
    );
}

function TDXPContent() {
    useWelcomeToast();
    return (
        <div className="min-h-screen overflow-x-hidden bg-transparent font-sans text-white antialiased">
            <Navbar />
            <main>
                {/* 1. Hero */}
                <HeroSection />
                {/* 2. Giới thiệu · Sứ mệnh – Tầm nhìn – Giá trị */}
                <AboutSection />
                {/* 3. Thành tựu nổi bật (counter) */}
                <ImpactMetrics />
                {/* 4. Hệ sinh thái sản phẩm (Bento) */}
                <ProductEcosystem />
                {/* 5. Công nghệ sử dụng (carousel) */}
                <TechStack />
                {/* 6. Đội ngũ — sơ đồ tổ chức (#org) + thành viên (#team) */}
                <DeferredSection id="org">
                    <OrgGraph />
                </DeferredSection>
                <TeamSection />
                {/* 7. Dự án đang triển khai (timeline ngang) */}
                <ProjectTimeline />
                {/* 8. AI & Innovation Lab */}
                <AILab />
                {/* 9. Văn hoá (Masonry gallery) */}
                <CultureGallery />
                {/* 10. Roadmap 2026–2027 */}
                <Roadmap />
            </main>
            {/* 11. Footer */}
            <Footer />
        </div>
    );
}

export default function TDXPIndex() {
    return (
        <>
            <Head title="Phòng Công Nghệ" />
            <ToastProvider>
                <TDXPContent />
            </ToastProvider>
        </>
    );
}
