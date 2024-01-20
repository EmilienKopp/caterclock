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

<table class="mt-16">
    <thead>
        <tr>
            <th>Date</th>
            <th>Start</th>
            <th>End</th>
            <th>Project</th>
            <th>Duration</th>
        </tr>
    </thead>
    <tbody>
    {#each entries ?? [] as entry}
        <tr>
            <td>{new Date(entry.date).toLocaleDateString()}</td>
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

<style>
    .in {
        color: green;
    }

    .out {
        color: red;
    }

    .in, .out {
        font-weight: bold;
        font-size: larger;
    }

    th,td {
        padding: 10px;
        border: 1px solid #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: rgba(250,250,250, 0.05);
    }

    th {
        color: white;
    }
</style>