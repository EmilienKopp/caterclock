<script lang="ts">
    import MonthTable from "$components/Tables/MonthTable.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import { DailyLog } from "$lib/models/DailyLog.svelte";

    interface Props {
        dailyLogs?: {[key:string]: DailyLog[]};
        date?: Date;
    }

    let { dailyLogs, date = new Date() }: Props = $props();

</script>

<AuthenticatedLayout>
    <div class="w3/4 mx-auto pt-16">
        <MonthTable headers={["Date", "Project", "Activity", "Duration"]} data={dailyLogs} {date} />
    </div>
</AuthenticatedLayout>

