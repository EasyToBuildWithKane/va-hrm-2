# UI/UX Audit — Cổng Phòng Công Nghệ (TDXP)

> Ngày kiểm toán: 2026-05-31 · Phạm vi: trang `/phongcongnghe` (`resources/js/Pages/TDXP/**`).
> Vai trò: Senior Product Designer / UX Architect / Frontend Architect.

## 1. Bối cảnh dự án

- **Backend:** Laravel 10 modular monolith (HRM) — `modules/` (Employee, Department, Organization, Leave…).
- **Frontend:** React 18 + Inertia 2 + Tailwind CSS v4 + Vite 5.
- **Thư viện UX đã cài:** `framer-motion`, `@xyflow/react` (React Flow) + `dagre`, `react-countup`, `react-intersection-observer`, `@radix-ui/react-dialog`, `@radix-ui/react-scroll-area`, `lucide-react`, `class-variance-authority`, `tailwind-merge`.
- **Điểm vào:** `routes/web.php` → `TDXPController` → `Inertia::render('TDXP/Index')`.
- **Cấu trúc trang:** Navbar → Hero → ImpactMetrics → OrgGraph → TeamSection → TechStack → ProjectShowcase → AILab → Roadmap → Footer.

Trang đã ở mức hoàn thiện khá cao (single-page, scroll-spy, mock data tách riêng trong `resources/js/data/`). Audit này tập trung vào **khoảng trống còn lại**, không phải xây mới từ đầu.

## 2. Phát hiện theo nhóm

### 2.1 UX
| # | Vấn đề | Mức độ | Trạng thái |
|---|--------|--------|------------|
| UX-1 | Navbar mobile: không có menu hamburger — chỉ hiển thị nav ở `lg`, mobile mất điều hướng giữa các section | Cao | ⏳ Còn lại |
| UX-2 | Không có nút "về đầu trang" / progress chỉ báo cuộn | Trung bình | ⏳ |
| UX-3 | Scroll-spy: nav không highlight section đang xem (`aria-current`) | Trung bình | ⏳ |
| UX-4 | MemberDrawer không khoá scroll nền & chưa kiểm tra focus-trap đầy đủ | Trung bình | ⏳ Cần kiểm |
| UX-5 | OrgGraph thiếu search / highlight reporting-line / nhóm phòng ban (yêu cầu Phase 7) | Cao | ⏳ |

### 2.2 UI & nhất quán
| # | Vấn đề | Trạng thái |
|---|--------|------------|
| UI-1 | **Lệch màu thương hiệu:** token `secondary` dùng `#0f172a` thay vì brand `#102A43` | ✅ Đã sửa (`app.css`) |
| UI-2 | Thiếu thang token chính thức (radius, shadow, spacing) — giá trị rải rác trong class | ✅ Đã thêm token vào `@theme` |
| UI-3 | TechStack render text-only, **không có logo** công nghệ | ✅ Đã thay bằng logo wall + bộ lọc |
| UI-4 | Heading section không có "eyebrow" (nhãn nhỏ) → phân cấp thị giác yếu | ✅ Đã thêm eyebrow cho các section |

### 2.3 Localization (Tiếng Việt) — Phase 2
| Thành phần | Chuỗi tiếng Anh | Trạng thái |
|-----------|------------------|------------|
| HeroSection | "Technology Driving Business Growth" | ✅ Đã dịch |
| TeamSection | "Meet the Team", "Portfolio showcase" | ✅ |
| TechStack | "Technology Stack", "Languages/Frameworks/…" | ✅ |
| ProjectShowcase | "Project Showcase", "Team:" | ✅ |
| AILab | "AI & Innovation Lab" | ✅ |
| Roadmap | "Roadmap" | ✅ |
| MemberDrawer | "About / Current Projects / Technology Expertise / Workload Overview / Achievements / Career Timeline / Personal Gallery / Personal Motto" | ✅ |
| TechOrbit | "Core / Technology Department" | ✅ |
| Footer | "Technology Department Experience Platform / Built for leadership showcase" | ✅ |

> Lưu ý: tên riêng kỹ thuật (React, Laravel, PMO, AI Lab…) giữ nguyên theo chủ ý.

### 2.4 Spacing & layout
- Nhịp dọc nhất quán (`py-20 md:py-28`, `max-w-7xl`) — **tốt**.
- Hero `min-h-screen` trên màn thấp (<700px) gây tràn — nên cân nhắc `min-h-[640px]`. ⏳

### 2.5 Mobile / Responsive
- ✅ OrgGraph có nhánh mobile riêng (accordion) thay cho React Flow.
- ⏳ UX-1 (navbar mobile) là khoảng trống lớn nhất về mobile.
- ⏳ TechOrbit dùng `max-w-[420px]` — cần kiểm tràn trên màn <360px.

### 2.6 Accessibility (mục tiêu WCAG 2.1 AA)
| # | Vấn đề | Trạng thái |
|---|--------|------------|
| A11Y-1 | Avatar nên có `alt` = tên người | ✅ `MemberCard`, `MemberDrawer`, avatar dự án |
| A11Y-2 | Logo công nghệ cần `role="img"` + `aria-label` | ✅ Đã thêm trong `TechLogo` |
| A11Y-3 | `prefers-reduced-motion` cho marquee/particle/orbit/counter | ✅ Marquee (CSS) + AILab canvas + TechOrbit + ImpactMetrics counter |
| A11Y-4 | Tương phản: text `white/40` trên nền `secondary` ~ ranh giới AA cho cỡ nhỏ | ⏳ Cần đo bằng Lighthouse |
| A11Y-5 | Bộ lọc (Team/Tech) chưa có `aria-pressed` | ✅ Đã thêm; OrgGraph search có `aria-label` |

### 2.7 Performance
- ✅ Code-splitting tự nhiên qua Inertia `import.meta.glob` (mỗi section 1 chunk).
- ✅ `OrgGraph` (React Flow ~270 kB / 89 kB gzip) **đã tách lazy** (`React.lazy` + `DeferredSection` intersection) — không còn trong initial bundle.
- ✅ `Dashboard` (Recharts ~384 kB / 112 kB gzip) **lazy-load**, chỉ tải khi cuộn tới.
- ✅ Logo dạng SVG đơn sắc (mask) — nhẹ, đổi màu bằng CSS, không tải PNG.
- ✅ AILab canvas dừng `requestAnimationFrame` khi ngoài viewport (IntersectionObserver) + reduced-motion.
- ✅ Ảnh remote: `loading="lazy"` + `decoding="async"` + `width/height` (giảm CLS); component `Img` có skeleton.

## 3. Trạng thái triển khai theo Phase

| Phase | Nội dung | Trạng thái |
|------|----------|-----------|
| 1 | Audit | ✅ Tài liệu này |
| 2 | Bản địa hoá tiếng Việt | ✅ Toàn bộ chuỗi hiển thị |
| 3 | Design system + tokens `#102A43` | ✅ `app.css` + `design-system.md` |
| 4 | Thư viện 50 logo + TechStack mới | ✅ |
| 5 | Animations (marquee, magnetic, orbit, particle, parallax counter) | ✅ Đã có sẵn + bổ sung |
| 6 | Team experience (card + drawer hồ sơ) | ✅ Đã có sẵn |
| 7 | Org graph (node types, search, highlight tuyến báo cáo, legend) | ✅ |
| 8 | Executive dashboard (Recharts + empty state, lazy) | ✅ |
| 9 | Micro-interactions (toast, skeleton, success check, button feedback, reduced-motion) | ✅ |
| 10 | Performance (lazy React Flow/Recharts, pause canvas, lazy images, a11y) | ✅ Code xong; Lighthouse cần chạy thủ công |

## 4. Hướng dẫn đo Lighthouse (Phase 10)

Môi trường này không chạy headless Chrome, cần đo thủ công:

```bash
php artisan serve            # cổng 8000
npm run build                # build production assets
npx lighthouse http://localhost:8000/phongcongnghe --view --preset=desktop
```

Ngân sách bundle hiện tại (gzip): initial `app` ~114 kB; OrgGraph (89 kB) & Dashboard (112 kB) **tách lazy**, không tính vào initial. Mục tiêu Performance/Accessibility > 90.

## 5. Tổng hợp đã hoàn thành (đợt 1 + 2)

- Token màu thương hiệu `#102A43` + thang radius/shadow/spacing + tiện ích glass/skeleton/logo-mask (`resources/css/app.css`).
- 50 logo công nghệ chính thức (SVG) → `public/assets/tech-logos/`; TechStack logo wall + bộ lọc + marquee.
- Bản địa hoá tiếng Việt toàn bộ; Navbar mobile + scroll-spy.
- OrgGraph nâng cấp; Dashboard điều hành (Recharts); hệ Toast/Skeleton/SuccessCheck; reduced-motion toàn cục; lazy-load chunk nặng.
- Tài liệu: `docs/uiux/ui-ux-audit.md`, `docs/uiux/design-system.md`.
