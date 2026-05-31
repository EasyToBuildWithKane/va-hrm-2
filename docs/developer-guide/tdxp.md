# TDXP — Technology Department Experience Platform

> Landing nội bộ tại **`GET /phongcongnghe`**. Mock data; thiết kế để thay bằng Admin CMS sau.

## Stack

- Laravel: [TDXPController](../../app/Http/Controllers/TDXPController.php), Inertia middleware
- Frontend: `resources/js/Pages/TDXP/`, Vite + React + Tailwind + Framer Motion + React Flow

## Data layer (CMS-ready)

| File | Nội dung |
|------|----------|
| `resources/js/data/team.ts` | 12 thành viên (avatar, bio, projects, gallery, …) |
| `resources/js/data/projects.ts` | 10 dự án |
| `resources/js/data/technologies.ts` | 25 công nghệ + orbit hero |
| `resources/js/data/organization.ts` | Org graph nodes/edges |
| `resources/js/data/metrics.ts` | KPI impact |
| `resources/js/data/roadmap.ts` | Lộ trình |
| `resources/js/data/aiLab.ts` | AI Lab capabilities |

Export tập trung: `resources/js/data/index.ts`.

## Sections (story flow)

1. Hero + Tech Orbit  
2. Impact metrics  
3. Organization graph  
4. Team portfolio + drawer detail  
5. Technology stack  
6. Project showcase  
7. AI & Innovation Lab  
8. Roadmap  

## Dev

```bash
npm run dev
php artisan serve
```

Truy cập: `/phongcongnghe`.
