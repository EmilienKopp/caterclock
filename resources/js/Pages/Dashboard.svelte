<script lang="ts">
    import Clock from '$components/App/TimeLog/Clock.svelte';
    import DashboardItem from '$components/UI/DashboardItem.svelte';
    import ClockingForm from '$components/App/TimeLog/ClockingForm.svelte';
    import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.svelte';
    import ElapsedMarker from '$components/App/TimeLog/ElapsedMarker.svelte';
    import { writable } from 'svelte/store';
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import NewLogModal from '$components/Modals/NewLogModal.svelte';

    export let auth: any = null;

    const dropzones = writable(Array(4).fill(null));

    let newLogModalOpen = false;

</script>

<AuthenticatedLayout data={ {auth} }>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard - {auth.user.name}</h2>


    <div class="py-12" on:dragover|preventDefault role="application" >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-5 sm:grid-cols-3 grid-cols-1">
            <DashboardItem>
                <Clock />
            </DashboardItem>
            <DashboardItem>
                <ClockingForm>
                    <ElapsedMarker slot="indicator"/>
                </ClockingForm>
            </DashboardItem>
            <DashboardItem>
                <PrimaryButton on:click={() => newLogModalOpen = true}>Manually add a log entry</PrimaryButton>
            </DashboardItem>
        </div>
    </div>

    <NewLogModal bind:open={newLogModalOpen} />
</AuthenticatedLayout>

