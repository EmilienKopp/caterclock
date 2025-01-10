import { Writable, derived, readable, writable } from "svelte/store";
import { Toastable, toastable } from "./utils/Toast";

import { page } from "@inertiajs/svelte";
import dayjs from "dayjs";
import localeData from "dayjs/plugin/localeData";
import { Toast } from "flowbite-svelte";
import { isEmpty } from "lodash";

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
    return dayjs($datetime).format("HH:mm:ss");
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

export const queryParams = derived(page, ($page: any) => {
    const search = $page.url.split("?")[1];
    const searchParams = new URLSearchParams(search);
    const params = Object.fromEntries(searchParams);
    return isEmpty(params) ? null : params;
});