import pluralize from 'pluralize';

export function leftPad(str: string | number, char: string | Number, length: number): string {
    str = str.toString();
    char = char.toString();
    while (str.length < length) {
        str = char + str;
    }
    return str;
}

export function aAn(str: string): string {
    const article = (['a', 'e', 'i', 'o', 'u'].includes(str[0].toLowerCase())) ? 'an' : 'a';
    return `${article} ${str}`;
}

export function keyNameToModel(str: string) {
    const singular = pluralize.singular(str);
    const pascalCased = singular.charAt(0).toUpperCase() + singular.slice(1);
    return pascalCased;
} 