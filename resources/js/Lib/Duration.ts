import { leftPad } from "./Text";

export class Duration {
    private seconds = 0;

    constructor(seconds: number) {
        this.seconds = seconds;
    }

    public getSeconds(): number {
        return this.seconds;
    }

    public getMinutes(): number {
        return Math.floor(this.seconds / 60);
    }

    public static getMinutes(seconds: number): number {
        const duration = new Duration(seconds);
        return duration.getMinutes();
    }

    public static flooredToMinute(seconds: number): number {
        const duration = new Duration(seconds);
        return Math.floor(duration.getMinutes()) * 60;
    }

    public static nextRoundMinute(seconds: number): number {
        const duration = new Duration(seconds);
        let minutes = duration.getMinutes();
        minutes++;
        return minutes * 60;
    }

    public getHours(): number {
        return Math.floor(this.seconds / 3600);
    }

    public static getHours(seconds: number): number {
        const duration = new Duration(seconds);
        return duration.getHours();
    }

    public getDays(): number {
        return Math.floor(this.seconds / 86400);
    }

    public toHHMM(): string {
        const hours = this.getHours();
        const minutes = this.getMinutes() % 60;
        return `${leftPad(hours,0,2)}:${leftPad(minutes,0,2)}`;
    }

    public static toHHMM(seconds: number): string {
        const duration = new Duration(seconds);
        return duration.toHHMM();
    }

    public toHHMMSS(): string {
        const hours = this.getHours();
        const minutes = this.getMinutes() % 60;
        const seconds = this.getSeconds() % 60;

        return `${leftPad(hours,0,2)}:${leftPad(minutes,0,2)}:${leftPad(seconds,0,2)}`;
    }
}