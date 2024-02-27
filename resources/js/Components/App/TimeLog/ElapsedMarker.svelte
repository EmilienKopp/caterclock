<script lang="ts">
    import { Duration } from "$lib/Duration";
    import dayjs from "dayjs";
    import { getContext } from "svelte";

    export let running: any = getContext("running")

    let secondsSinceLastClockIn = 0;
    setInterval(() => {
        secondsSinceLastClockIn = (dayjs().diff(dayjs(running.in_time), "second"));
    }, 1000)
</script>

{#if running}
    <div class="flex items-center justify-center">
        <span class="loading loading-infinity loading-xs mr-2"></span>
        <p class="text-xs text-gray-500 ml-2">Running for {Duration.toHrMinString(secondsSinceLastClockIn)}</p>
    </div>
{:else}
    <div class="flex items-center justify-center">
        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
        <p class="text-xs text-gray-500 ml-2">Not Running</p>
    </div>
{/if}
