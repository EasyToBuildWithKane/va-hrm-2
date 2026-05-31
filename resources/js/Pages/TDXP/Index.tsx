import { Head } from '@inertiajs/react';
import { Navbar } from './components/Navbar';
import { HeroSection } from './components/HeroSection';
import { AboutSection } from './components/AboutSection';
import { ImpactMetrics } from './components/ImpactMetrics';
import { ProductEcosystem } from './components/ProductEcosystem';
import { TechStack } from './components/TechStack';
import { OrgChart } from './components/OrgChart';
import { TeamSection } from './components/TeamSection';
import { ProjectTimeline } from './components/ProjectTimeline';
import { AILab } from './components/AILab';
import { CultureGallery } from './components/CultureGallery';
import { Roadmap } from './components/Roadmap';
import { Footer } from './components/Footer';
import { ToastProvider, useWelcomeToast } from '@/components/ui/Toast';

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
                {/* 4. Hệ sinh thái sản phẩm (slider) */}
                <ProductEcosystem />
                {/* 5. Công nghệ sử dụng (carousel) */}
                <TechStack />
                {/* 6. Đội ngũ — sơ đồ tổ chức (#org) + thành viên (#team) */}
                <OrgChart />
                <TeamSection />
                {/* 7. Dự án đang triển khai (timeline 3 nhóm) */}
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
