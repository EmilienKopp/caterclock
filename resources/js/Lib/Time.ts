import type { Timezone } from '$lib/types/timezones';
import dayjs from 'dayjs';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc';

dayjs.extend(utc);
dayjs.extend(timezone);

export class Time {
    #time: dayjs.Dayjs;
    #tz: Timezone;

    constructor(time: dayjs.Dayjs, tz: Timezone) {
        this.#time = time;
        this.#tz = tz;
    }

    static now(tz?: Timezone) {
        return new Time(dayjs().tz(tz), tz || 'UTC');
    }

    static tz(): Timezone | string {
        return dayjs.tz.guess() as Timezone;
    }

    static local(time: string, tz: Timezone, format?: string) {
        return dayjs.utc(time)
                    .tz(tz)
                    .format(format || 'HH:mm');
    }

    public time(format?: string) {
        return this.#time.format(format || 'YYYY-MM-DD HH:mm:ss');
    }

    public tz() {
        return this.#tz;
    }
}

