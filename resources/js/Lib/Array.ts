import { resolveNestedValue } from "supakit-eloquent/lib/objects";

export function groupBy<T>(
    arr: T[], 
    key: string, 
    options?: { mapToNestedProp?: string, asEntries?: boolean }
): { [key: string]: T[] }[] | { [key: string]: T[] } | [string, T[]][] | ArrayLike<T[]>
{
    const reduced = arr.reduce((grouped: { [key: string]: T[] }, current: any, index: number): any => {
        let finalKey = current[key];
        if(options?.mapToNestedProp) {
            finalKey = resolveNestedValue(current, options?.mapToNestedProp);
        }

        if(!finalKey) {
            return grouped;
        }

        if (!grouped[finalKey]) {
            grouped[finalKey] = [];
        }
        grouped[finalKey].push(current);
        return grouped;
    }, {});
    if(options?.asEntries) {
        return Object.entries(reduced);
    }
    return reduced;
}