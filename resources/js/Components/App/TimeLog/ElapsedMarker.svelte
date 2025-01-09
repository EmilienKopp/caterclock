<script lang="ts">
    import { Duration } from "$lib/duration";
    import dayjs from "dayjs";
    import { getContext } from "svelte";
    import { twMerge } from "tailwind-merge";

    export let running: any = getContext("running")

    let secondsSinceLastClockIn = 0;
    setInterval(() => {
        if(running?.in_time)
            secondsSinceLastClockIn = (dayjs().diff(dayjs(running.in_time), "second"));
    }, 1000)

    const classes = twMerge("flex items-center justify-center",$$restProps.class);
</script>

{#if running}
    <div class={classes} >
        <span class="loading loading-infinity loading-xs mr-2"></span>
        <p class="text-xs text-gray-500 ml-2">On {running.project.name} for {Duration.toHrMinString(secondsSinceLastClockIn)}</p>
    </div>
{:else}
    <div class={classes} >
        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
        <p class="text-xs text-gray-500 ml-2">Not Running</p>
    </div>
{/if}
