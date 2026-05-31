import { cn } from '@/lib/utils';
import { cva, type VariantProps } from 'class-variance-authority';
import { forwardRef, type ButtonHTMLAttributes } from 'react';

const buttonVariants = cva(
    'inline-flex items-center justify-center gap-2 rounded-full text-sm font-semibold transition-all duration-150 active:scale-[0.97] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 disabled:pointer-events-none disabled:opacity-50',
    {
        variants: {
            variant: {
                primary: 'bg-glow text-[#3a0016] shadow-lg shadow-glow/30 hover:bg-white hover:shadow-glow/50',
                secondary: 'bg-white/10 text-white hover:bg-white/20',
                outline: 'border border-white/25 bg-white/10 text-white backdrop-blur-sm hover:border-glow/60 hover:bg-white/15',
                ghost: 'text-white hover:bg-white/10',
            },
            size: {
                sm: 'h-9 px-4',
                md: 'h-11 px-6',
                lg: 'h-12 px-8 text-base',
            },
        },
        defaultVariants: {
            variant: 'primary',
            size: 'md',
        },
    },
);

export interface ButtonProps
    extends ButtonHTMLAttributes<HTMLButtonElement>,
        VariantProps<typeof buttonVariants> {}

export const Button = forwardRef<HTMLButtonElement, ButtonProps>(
    ({ className, variant, size, ...props }, ref) => (
        <button ref={ref} className={cn(buttonVariants({ variant, size }), className)} {...props} />
    ),
);
Button.displayName = 'Button';
