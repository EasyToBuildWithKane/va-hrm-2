import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]): string {
    return twMerge(clsx(inputs));
}

export function avatarUrl(name: string, size = 256): string {
    const encoded = encodeURIComponent(name.replace(/\s+/g, '+'));
    return `https://ui-avatars.com/api/?name=${encoded}&background=9A0036&color=fff&size=${size}&bold=true`;
}

export function coverUrl(seed: string): string {
    return `https://picsum.photos/seed/${encodeURIComponent(seed)}/1200/400`;
}

export function galleryUrl(seed: string, index: number): string {
    return `https://picsum.photos/seed/${encodeURIComponent(seed)}-${index}/800/600`;
}
