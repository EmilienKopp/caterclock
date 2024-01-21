<script lang="ts">
    import { Duration } from "$lib/Duration";
    import { elapsedSeconds } from "$lib/stores";

    export let entries: any[];


    entries = entries.map( (entry: any) => {
        if(entry.out_time && new Date(entry.out_time) < new Date(entry.in_time)) {
            // Display the out time as if on the next day but with hours+24
            entry.out_time = new Date(entry.out_time).setHours(new Date(entry.out_time).getHours() + 24);
        }
        return entry
    })

</script>

<div class="w-1/3 overflow-x-auto mx-auto">
    <table class="mt-16 table table-pin-cols">
        <thead>
            <tr>
                <th>Start</th>
                <th>End</th>
                <th>Project</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>
        {#each entries ?? [] as entry}
            <tr>
                <td>{new Date(entry.in_time).toLocaleTimeString()}</td>
                <td>{entry.out_time ? new Date(entry.out_time).toLocaleTimeString() : "-"}</td>
                <td>{entry.project?.name ?? ""}</td>
                <td>
                    {Duration.toHHMM(entry.total_duration ?? $elapsedSeconds)}
                </td>
            </tr>     
        {/each}
        </tbody>
    </table>
</div>