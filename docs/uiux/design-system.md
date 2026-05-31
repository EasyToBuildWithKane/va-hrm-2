# Design System — Cổng Phòng Công Nghệ (TDXP)

> Nguồn chân lý: `resources/css/app.css` (token Tailwind v4 `@theme`). Tài liệu này mô tả hệ thống đã
> được hiện thực trong code, lấy cảm hứng Apple / Linear / Vercel / Stripe.

## 1. Nguyên tắc thiết kế
- **Premium & tĩnh lặng:** nhiều khoảng trắng, bo góc lớn, đổ bóng mềm, chuyển động có chủ đích.
- **Tương phản thương hiệu:** nền sáng `surface` + điểm nhấn đỏ mận `primary`; section "tech/AI" đảo nền sang navy `secondary`.
- **Chuyển động phục vụ nội dung:** mọi animation tôn trọng `prefers-reduced-motion`.
- **Accessibility WCAG 2.1 AA** là ràng buộc, không phải tuỳ chọn.

## 2. Color tokens

| Token | Hex | Vai trò |
|-------|-----|---------|
| `primary` | `#9A0036` | Hành động chính, nhấn mạnh, link, đỏ mận thương hiệu |
| `primary-soft` | `#C41250` | Gradient/hover sáng hơn của primary |
| `primary-foreground` | `#FFFFFF` | Chữ trên nền primary |
| `secondary` | `#102A43` | Navy — chữ chính, nền section tech/AI |
| `accent` | `#E11D48` | Điểm nhấn phụ, glow, trạng thái nổi bật |
| `surface` | `#F7F9FC` | Nền sáng chính |
| `surface-2` | `#EEF2F8` | Nền phụ, skeleton |

Sử dụng qua Tailwind: `bg-primary`, `text-secondary`, `border-secondary/10`, `bg-surface`…
Opacity modifier (`/10`, `/60`) là cách chính tạo sắc độ — không định nghĩa biến riêng cho từng mức.

**Dark mode:** thêm class `.dark` lên `<html>` → `--color-bg` chuyển `#0A1626`. (Token đã sẵn sàng; toggle UI là việc Phase sau.)

## 3. Typography
- **Font:** `Inter`, fallback `Be Vietnam Pro` (hỗ trợ dấu tiếng Việt), rồi system-ui.
- **Thang dùng trong code (Tailwind):**

| Cấp | Class | Dùng cho |
|-----|-------|---------|
| Display/H1 | `text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight` | Hero |
| H2 | `text-3xl md:text-4xl font-bold` | Tiêu đề section |
| Eyebrow | `text-sm font-bold tracking-[0.2em] text-primary/accent` | Nhãn nhỏ trên H2 |
| Body | `text-base / text-sm leading-relaxed` | Nội dung |
| Caption | `text-xs text-secondary/50` | Chú thích |
| Số liệu | `tabular-nums font-bold` | Counter, % tiến độ |

## 4. Spacing & layout
- **Container:** `max-w-7xl mx-auto px-4 md:px-8`.
- **Nhịp section:** `py-20 md:py-28`, `scroll-mt-24` để bù navbar fixed.
- **Grid card:** `gap-4` (md) → `gap-6` (lg).

## 5. Border radius (token `--radius-*`)
`sm 0.5rem` · `md 0.75rem` · `lg 1rem` · `xl 1.5rem` · `2xl 2rem`.
Card mặc định `rounded-2xl`; pill/badge `rounded-full`.

## 6. Elevation (shadow tokens)
| Token | Dùng cho |
|-------|---------|
| `--shadow-soft` | Card nghỉ |
| `--shadow-lift` | Card hover |
| `--shadow-glow` | Hiệu ứng glow đỏ mận khi hover (logo, CTA) |

## 7. Glassmorphism
Tiện ích `.glass` = nền trắng 6% + `backdrop-blur(12px)` + viền trắng 12%.
Dùng trên nền tối (TechStack, AILab) cho card nổi.

## 8. Motion
- **Easing chuẩn:** `[0.22, 1, 0.36, 1]` (ease-out mượt) cho enter; spring `stiffness 400 / damping 28` cho hover card.
- **Stagger:** `staggerChildren 0.08–0.12` cho list/grid.
- **Marquee:** `.animate-marquee` (40s) / `.animate-marquee-slow` (60s), `.pause-on-hover` để dừng khi rê chuột.
- **Reduced motion:** `@media (prefers-reduced-motion: reduce)` tắt marquee/shimmer.

## 9. Iconography & logo
- **Icon UI:** `lucide-react` (stroke 1.5–2, cỡ `h-4/6/8`).
- **Logo công nghệ:** SVG đơn sắc (simple-icons) tại `public/assets/tech-logos/`. Render bằng tiện ích `.tech-logo` (CSS `mask`) → đổi màu theo `currentColor` (trắng mặc định, `accent` khi hover). 50 logo: ngôn ngữ, frontend, backend, database, devops, cloud, AI.

## 10. Component primitives (`resources/js/components/ui/`)
`Button` (cva variants, có `active:scale` feedback), `Badge`, `Drawer` (Radix Dialog), `ProgressBar`,
`Toast` (`ToastProvider` + `useToast()`), `Skeleton` (shimmer), `Img` (ảnh lazy + skeleton + alt),
`SuccessCheck` (tick SVG path-draw). Empty state dùng `Pages/TDXP/components/EmptyState`.
Mọi component mới nên tái sử dụng các primitive này + helper `cn()` (`clsx` + `tailwind-merge`).

## 11. Quy ước
- Token màu/khoảng cách → luôn qua class Tailwind, tránh hex inline (ngoại lệ: gradient động trong canvas/SVG).
- Section đảo nền tối: `bg-secondary text-white`, dùng `white/xx` cho sắc độ.
- Mỗi section có `id` + `scroll-mt-24` để navbar scroll-spy hoạt động.

## 12. Data viz (Dashboard)

- **Recharts** (Pie/Bar/RadialBar) với palette `['#9A0036','#E11D48','#102A43','#2563EB','#7C3AED','#0891B2']`.
- Bar bo góc `radius`, tooltip bo tròn (`--shadow-lift`), `isAnimationActive` gắn `useReducedMotion`.
- Dashboard + OrgGraph **lazy-load** (`React.lazy` + `DeferredSection`) để initial bundle nhẹ.
