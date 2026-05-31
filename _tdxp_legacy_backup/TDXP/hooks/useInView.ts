import { useInView as useInViewObserver } from 'react-intersection-observer';

export function useInView(options?: Parameters<typeof useInViewObserver>[0]) {
    return useInViewObserver(options);
}
