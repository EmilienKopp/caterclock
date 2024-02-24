export interface InertiaForm extends Partial<HTMLFormElement> {
    errors?: { [key: string]: string },
    [key: string]: any,
    reset: () => void,
}

export type RouteItem = {
    label: string,
    route: string,
    params?: any,
    url?: string,
    icon?: string,
    children?: RouteItem[],
    availableTo?: string[] | number[],
}

export type DataListDefinition = {
    [key:string]: {
        label: string;
        formatter?: (value: any) => string;
    }
}


export type ButtonVariant = "primary" | "secondary" | "danger" | "ghost" | "outline" | "link" | "glass"
| "success" | "warning" | "info" | "accent" | "warning" | "error";

export type Size = "xs" | "sm" | "md" | "lg" | "xl" | "2xl" | "3xl" | "4xl" | "5xl" | "6xl" | "7xl" | "8xl" | "9xl";

export type ButtonSize = "xs" | "sm" | "md" | "lg" | "xl";

export type ButtonShape = "wide" | "circle" | "square" | "block" | undefined;