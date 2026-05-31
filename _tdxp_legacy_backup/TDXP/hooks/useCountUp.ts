import { useInView } from './useInView';

export function useCountUpTrigger(threshold = 0.2) {
    const { ref, inView } = useInView({ triggerOnce: true, threshold });
    return { ref, inView };
}
