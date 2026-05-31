import * as Dialog from '@radix-ui/react-dialog';
import { X } from 'lucide-react';
import { AnimatePresence, motion } from 'framer-motion';
import { cn } from '@/lib/utils';

interface DrawerProps {
    open: boolean;
    onOpenChange: (open: boolean) => void;
    children: React.ReactNode;
    title?: string;
    className?: string;
}

export function Drawer({ open, onOpenChange, children, title, className }: DrawerProps) {
    return (
        <Dialog.Root open={open} onOpenChange={onOpenChange}>
            <AnimatePresence>
                {open && (
                    <Dialog.Portal forceMount>
                        <Dialog.Overlay asChild>
                            <motion.div
                                className="fixed inset-0 z-50 bg-black/55 backdrop-blur-sm"
                                initial={{ opacity: 0 }}
                                animate={{ opacity: 1 }}
                                exit={{ opacity: 0 }}
                            />
                        </Dialog.Overlay>
                        <Dialog.Content asChild>
                            <motion.div
                                className={cn(
                                    'fixed z-50 flex flex-col bg-[#3a0016] text-white shadow-2xl',
                                    'inset-x-0 bottom-0 max-h-[92vh] rounded-t-3xl md:inset-y-0 md:left-auto md:right-0 md:max-h-none md:w-full md:max-w-xl md:rounded-none md:rounded-l-3xl',
                                    className,
                                )}
                                initial={{ x: '100%', y: '100%' }}
                                animate={{ x: 0, y: 0 }}
                                exit={{ x: '100%', y: '100%' }}
                                transition={{ type: 'spring', damping: 28, stiffness: 260 }}
                            >
                                <div className="flex items-center justify-between border-b border-white/10 px-6 py-4 md:px-8">
                                    {title ? (
                                        <Dialog.Title className="text-lg font-semibold text-white">
                                            {title}
                                        </Dialog.Title>
                                    ) : (
                                        <Dialog.Title className="sr-only">Chi tiết</Dialog.Title>
                                    )}
                                    <Dialog.Close asChild>
                                        <button
                                            type="button"
                                            className="rounded-full p-2 text-white/60 hover:bg-white/10 hover:text-white"
                                            aria-label="Đóng"
                                        >
                                            <X className="h-5 w-5" />
                                        </button>
                                    </Dialog.Close>
                                </div>
                                <div className="flex-1 overflow-y-auto overscroll-contain">{children}</div>
                            </motion.div>
                        </Dialog.Content>
                    </Dialog.Portal>
                )}
            </AnimatePresence>
        </Dialog.Root>
    );
}
