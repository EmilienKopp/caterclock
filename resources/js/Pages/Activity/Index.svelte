<script lang="ts">
    import MonthTable from "$components/Tables/MonthTable.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import type { DailyLog } from "$models";

    interface Props {
        dailyLogs?: DailyLog[];
        date?: Date;
    }

    let { dailyLogs = [], date = new Date() }: Props = $props();

</script>

<AuthenticatedLayout>
    <div class="w3/4 mx-auto pt-16">
        <MonthTable headers={["Date", "Project", "Activity", "Duration"]} data={dailyLogs} {date} />
    </div>
</AuthenticatedLayout>

