

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