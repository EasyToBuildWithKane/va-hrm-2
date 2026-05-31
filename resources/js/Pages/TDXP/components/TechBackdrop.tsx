interface TechBackdropProps {
    /** Cường độ lưới (0–1). */
    gridOpacity?: number;
}

/**
 * Nền animation "tech" tái sử dụng: lưới quét động + 2 quầng sáng trôi.
 * Đặt làm con đầu tiên của một <section className="relative">; nội dung phía sau
 * nên nằm trong container `relative` để hiển thị trên nền. Tôn trọng reduced-motion
 * (các animation tự tắt qua app.css).
 */
export function TechBackdrop({ gridOpacity = 0.25 }: TechBackdropProps) {
    return (
        <div className="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden>
            <div className="bg-mesh-animated absolute inset-0" style={{ opacity: gridOpacity }} />
            <div
                className="animate-glow absolute -top-24 right-[-6%] h-72 w-72 rounded-full blur-3xl"
                style={{ background: 'radial-gradient(circle, rgba(255,92,138,0.16), transparent 70%)' }}
            />
            <div
                className="animate-glow absolute bottom-[-12%] left-[-6%] h-80 w-80 rounded-full blur-3xl"
                style={{ background: 'radial-gradient(circle, rgba(36,99,156,0.18), transparent 70%)' }}
            />
        </div>
    );
}
