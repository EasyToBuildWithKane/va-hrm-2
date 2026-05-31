/**
 * Mascot — nhân vật đại diện Phòng Công Nghệ (linh vật rồng lửa #9A0036).
 * Chỉ có 3 tư thế PNG (đã tách nền sẵn); map chúng theo ngữ cảnh sử dụng.
 */
export type MascotPose = 'wave' | 'stand' | 'run';

export const MASCOT: Record<MascotPose, string> = {
    wave: '/assets/brandva/mascot1.png', // hero, lời chào, welcome
    stand: '/assets/brandva/mascot2.png', // empty state, team, footer
    run: '/assets/brandva/mascot3.png', // loading
};

const POSES: MascotPose[] = ['wave', 'stand', 'run'];

/** Chọn tư thế ổn định (deterministic) theo seed — dùng làm avatar tạm cho nhân sự. */
export function mascotForSeed(seed: string): string {
    let hash = 0;
    for (let i = 0; i < seed.length; i += 1) {
        hash = (hash * 31 + seed.charCodeAt(i)) | 0;
    }
    return MASCOT[POSES[Math.abs(hash) % POSES.length]];
}
