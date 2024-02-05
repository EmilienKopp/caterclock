/**
 * Traverse an object and return the value of a deeply nested key
 * @param obj The object to resolve the value from
 * @param key the key to resolve, can be nested with dot notation
 * @returns the value of the deeply nested key
 */
export function resolveNestedValue(obj: any, key: string) {
    if(key.includes(".")) {
        const [first, ...rest] = key.split(".");
        return resolveNestedValue(obj[first], rest.join("."));
    }
    return obj?.[key];
}