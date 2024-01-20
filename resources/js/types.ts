

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