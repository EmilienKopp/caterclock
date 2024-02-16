import { Readable, Writable, derived, readable, writable } from "svelte/store";
import { Toastable, toastable } from "./Toast";

import { Toast } from "flowbite-svelte";
import dayjs from "dayjs";
import localeData from "dayjs/plugin/localeData";
import { page } from "@inertiajs/svelte";

dayjs.extend(localeData);
dayjs().localeData();

export const toast: Toastable<Toast> = toastable();

export const today = readable(dayjs().format("YYYY-MM-DD"));

export const thisMonth = readable( dayjs.months()[dayjs().month()]);

export const thisYear = readable(dayjs().year());

export const datetime = readable(new Date(), (set) => {
    const interval = setInterval(() => {
        set(new Date());
    }, 1000);
    
    return () => clearInterval(interval);
});

export const time = derived(datetime, ($datetime) => {
    return $datetime.toLocaleTimeString();
});

export const now = readable(Date.now(), (set) => {
    const interval = setInterval(() => {
        set(Date.now());
    }, 1000);
    return () => clearInterval(interval);
});

export const latestClockInTime: Writable<number> = writable();

export const elapsedSeconds = derived([now,latestClockInTime], ([$now,$latestClockInTime]) => {
    return $latestClockInTime ? Math.floor(($now - $latestClockInTime) / 1000) : 0;
});

export const elapsedMinutes = derived(elapsedSeconds, ($elapsedSeconds) => {
    return Math.floor(($elapsedSeconds % 3600) / 60);
});

export const elapsedHours = derived(elapsedSeconds, ($elapsedSeconds) => {
    return Math.floor($elapsedSeconds / 3600);
});

export const user = derived(page, ($page) => {
    return $page.props.auth.user;
});