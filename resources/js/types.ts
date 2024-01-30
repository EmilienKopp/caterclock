

export type Activity = {
    id: number,
    user_id: number,
    project_id: number,
    task_category_id: number,
    task_id?: number,
    date: string | Date,
    rate?: number,
    duration: number,
    project: Project,
    task_category: TaskCategory,
    dailyLog?: DailyLog,
}

export type Project = {
    id: number,
    name: string,
    company_id: number,
    user_id: number,
    description: string,
    start_date?: string | Date,
    end_date?: string | Date,
}

export type TaskCategory = {
    id: number,
    name: string,
    description?: string,
    color?: string,
}

export type DailyLog = {
    date: string | Date,
    project_id: number,
    project_name: string,
    total_seconds: number,
    user_id: number,
    user_name: string,
}


export interface InertiaForm extends Partial<HTMLFormElement> {
    errors?: { [key: string]: string },
    [key: string]: any,
    reset: () => void,
}

export type RouteItem = {
    label: string,
    route: string,
    url?: string,
    icon?: string,
}

export type ButtonVariant = "primary" | "secondary" | "danger" | "ghost" | "outline" | "link" | "glass"
| "success" | "warning" | "info" | "accent" | "warning" | "error";

export type Size = "xs" | "sm" | "md" | "lg" | "xl" | "2xl" | "3xl" | "4xl" | "5xl" | "6xl" | "7xl" | "8xl" | "9xl";

export type ButtonSize = "xs" | "sm" | "md" | "lg" | "xl";

export type ButtonShape = "wide" | "circle" | "square" | "block" | undefined;