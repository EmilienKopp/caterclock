

export function leftPad(str: string | number, char: string | Number, length: number): string {
    str = str.toString();
    char = char.toString();
    while (str.length < length) {
        str = char + str;
    }
    return str;
}