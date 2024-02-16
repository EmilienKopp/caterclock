<script lang="ts">
    import MonthTable from "$components/Tables/MonthTable.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import { groupBy } from "$lib/Array";

    export let dailyLogs: any[] = [];
    export let date: Date = new Date();

</script>

<AuthenticatedLayout>
    <div class="w3/4 mx-auto pt-16">
        <MonthTable headers={["Date", "Project", "Activity", "Duration"]} data={dailyLogs} {date} />
    </div>
</AuthenticatedLayout>

