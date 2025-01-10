<script lang="ts">
    import ClockingForm from '$components/App/TimeLog/ClockingForm.svelte';
    import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
    import Clock from '$components/App/TimeLog/Clock.svelte';
    import TimeLogTable from '$components/App/TimeLog/TimeLogTable.svelte';

    interface Props {
        entries: any[];
    }

    let { entries }: Props = $props();

</script>

<AuthenticatedLayout>
    <div class="flex flex-col justify-center items-center">

        <div class="flex flex-col items-center justify-around w-full border rounded-sm p-5">
            <Clock />
            <ClockingForm />
        </div>
        
        <TimeLogTable {entries} />

    </div>
</AuthenticatedLayout>

