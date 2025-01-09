<script lang="ts">
    import MiniButton from "$components/Buttons/MiniButton.svelte";
    import { Duration } from "$lib/utils/duration";
    import { Time } from "$lib/utils/time";
    import route from "$vendor/tightenco/ziggy/src/js";
    import dayjs from "dayjs";
    import timezone from "dayjs/plugin/timezone";
    import utc from "dayjs/plugin/utc";

    dayjs.extend(timezone);
    dayjs.extend(utc);

    export let entries: any[];
    let secondsSinceLastClockIn: number

    console.log(entries);

    $: entries = entries.map( (entry: any) => {
        if(entry.out_time && new Date(entry.out_time) < new Date(entry.in_time)) {
            // Display the out time as if on the next day but with hours+24
            entry.out_time = new Date(entry.out_time).setHours(new Date(entry.out_time).getHours() + 24);
        }
        return entry
    })

    $: console.log(entries);

    $: latestEntry = entries[0] ?? null;
    $: if(latestEntry) {
        secondsSinceLastClockIn = (dayjs().diff(dayjs(latestEntry.in_time), "second"));
        setInterval(() => {
            secondsSinceLastClockIn = (dayjs().diff(dayjs(latestEntry.in_time), "second"));
        }, 1000)
    }
</script>

<div class="sm:w-2/3 w-full overflow-x-auto mx-auto">
    <table class="mt-16 table table-pin-cols">
        <thead>
            <tr>
                <th>Start</th>
                <th>End</th>
                <th>Project</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        {#each entries ?? [] as entry}
            <tr>
                <td class="sm:text-sm text-xs">{Time.local(entry.in_time,entry.timezone)}</td>
                <td>{entry.out_time ? Time.local(entry.out_time,entry.timezone) : "-"}</td>
                <td>
                    <a href={route("projects.show",{project: entry.project.id})}>
                        {entry.project?.name ?? ""}
                    </a>
                </td>
                <td>
                    {#if entry.out_time}
                        {Duration.toHrMinString(entry.total_duration)}
                    {:else}
                        {Duration.toHrMinString(entry.total_duration ?? secondsSinceLastClockIn)}
                    {/if}
                </td>
                <td>
                    <MiniButton href={route("activities.show", {date: dayjs(entry.date).format("YYYY-MM-DD")})} class="bg-blue-500 hover:bg-blue-700">See</MiniButton>
                </td>
            </tr>     
        {/each}
        </tbody>
    </table>
</div>