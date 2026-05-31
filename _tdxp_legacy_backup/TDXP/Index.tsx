import { Head } from '@inertiajs/react';
import { Suspense, lazy } from 'react';
import { useInView } from 'react-intersection-observer';
import { Navbar } from './components/Navbar';
import { HeroSection } from './components/HeroSection';
import { ImpactMetrics } from './components/ImpactMetrics';
import { TeamSection } from './components/TeamSection';
import { TechStack } from './components/TechStack';
import { ProjectShowcase } from './components/ProjectShowcase';
import { AILab } from './components/AILab';
import { Roadmap } from './components/Roadmap';
import { Footer } from './components/Footer';
import { ToastProvider, useWelcomeToast } from '@/components/ui/Toast';
import { Skeleton } from '@/components/ui/Skeleton';

// Lazy: cô lập bundle nặng (React Flow ~275kB, Recharts) khỏi initial load.
const Dashboard = lazy(() => import('./components/Dashboard'));
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
                <HeroSection />
                <ImpactMetrics />
                <DeferredSection id="dashboard">
                    <Dashboard />
                </DeferredSection>
                <DeferredSection id="org">
                    <OrgGraph />
                </DeferredSection>
                <TeamSection />
                <TechStack />
                <ProjectShowcase />
                <AILab />
                <Roadmap />
            </main>
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
