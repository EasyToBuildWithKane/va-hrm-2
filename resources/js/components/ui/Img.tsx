import { useState } from 'react';
import { cn } from '@/lib/utils';

interface ImgProps extends React.ImgHTMLAttributes<HTMLImageElement> {
    /** Class cho khung bọc (hiển thị skeleton khi đang tải). */
    wrapperClassName?: string;
}

/**
 * Ảnh remote có skeleton shimmer tới khi load xong, lazy + async decode.
 * Dùng cho avatar/cover/gallery để giảm CLS và tạo cảm giác mượt.
 */
export function Img({ className, wrapperClassName, alt = '', onLoad, ...rest }: ImgProps) {
    const [loaded, setLoaded] = useState(false);
    return (
        <span className={cn('relative block overflow-hidden', !loaded && 'skeleton', wrapperClassName)}>
            <img
                {...rest}
                alt={alt}
                loading="lazy"
                decoding="async"
                onLoad={(e) => {
                    setLoaded(true);
                    onLoad?.(e);
                }}
                className={cn('transition-opacity duration-500', loaded ? 'opacity-100' : 'opacity-0', className)}
            />
        </span>
    );
}
