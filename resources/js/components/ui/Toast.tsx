import { createContext, useCallback, useContext, useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { X } from 'lucide-react';
import { SuccessCheck } from './SuccessCheck';

type ToastVariant = 'default' | 'success';

interface ToastItem {
    id: number;
    title: string;
    description?: string;
    variant: ToastVariant;
}

interface ToastOptions {
    title: string;
    description?: string;
    variant?: ToastVariant;
    duration?: number;
}

const ToastContext = createContext<(opts: ToastOptions) => void>(() => {});

export function useToast() {
    return useContext(ToastContext);
}

export function ToastProvider({ children }: { children: React.ReactNode }) {
    const [toasts, setToasts] = useState<ToastItem[]>([]);
    const idRef = useRef(0);

    const remove = useCallback((id: number) => {
        setToasts((list) => list.filter((t) => t.id !== id));
    }, []);

    const toast = useCallback(
        ({ title, description, variant = 'default', duration = 3500 }: ToastOptions) => {
            const id = ++idRef.current;
            setToasts((list) => [...list, { id, title, description, variant }]);
            window.setTimeout(() => remove(id), duration);
        },
        [remove],
    );

    return (
        <ToastContext.Provider value={toast}>
            {children}
            <div
                className="pointer-events-none fixed inset-x-0 bottom-0 z-[60] flex flex-col items-center gap-2 p-4 sm:inset-x-auto sm:right-0 sm:items-end"
                role="region"
                aria-label="Thông báo"
            >
                <AnimatePresence>
                    {toasts.map((t) => (
                        <motion.div
                            key={t.id}
                            layout
                            initial={{ opacity: 0, y: 24, scale: 0.95 }}
                            animate={{ opacity: 1, y: 0, scale: 1 }}
                            exit={{ opacity: 0, x: 40, scale: 0.95 }}
                            transition={{ type: 'spring', stiffness: 380, damping: 30 }}
                            className="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border border-secondary/10 bg-white p-4 shadow-[var(--shadow-lift)]"
                            role="status"
                        >
                            <span
                                className={
                                    t.variant === 'success'
                                        ? 'mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center text-emerald-500'
                                        : 'mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary'
                                }
                            >
                                {t.variant === 'success' ? (
                                    <SuccessCheck className="h-5 w-5" />
                                ) : (
                                    <span className="h-2 w-2 rounded-full bg-primary" />
                                )}
                            </span>
                            <div className="min-w-0 flex-1">
                                <p className="text-sm font-semibold text-secondary">{t.title}</p>
                                {t.description && <p className="mt-0.5 text-sm text-secondary/60">{t.description}</p>}
                            </div>
                            <button
                                type="button"
                                onClick={() => remove(t.id)}
                                className="shrink-0 rounded-md p-1 text-secondary/40 hover:bg-secondary/5 hover:text-secondary"
                                aria-label="Đóng thông báo"
                            >
                                <X className="h-4 w-4" />
                            </button>
                        </motion.div>
                    ))}
                </AnimatePresence>
            </div>
        </ToastContext.Provider>
    );
}

/** Phát một toast chào mừng đúng một lần khi trang tải xong. */
export function useWelcomeToast() {
    const toast = useToast();
    useEffect(() => {
        const t = window.setTimeout(
            () =>
                toast({
                    title: 'Chào mừng đến Phòng Công Nghệ',
                    description: 'Khám phá đội ngũ, dự án và công nghệ của chúng tôi.',
                    variant: 'success',
                }),
            900,
        );
        return () => window.clearTimeout(t);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);
}
