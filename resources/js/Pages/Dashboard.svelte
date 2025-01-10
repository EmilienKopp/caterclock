<script lang="ts">
    import { createBubbler, preventDefault } from 'svelte/legacy';

    const bubble = createBubbler();
    import Clock from '$components/App/TimeLog/Clock.svelte';
    import DashboardItem from '$components/UI/DashboardItem.svelte';
    import ClockingForm from '$components/App/TimeLog/ClockingForm.svelte';
    import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.svelte';
    import ElapsedMarker from '$components/App/TimeLog/ElapsedMarker.svelte';
    import { writable } from 'svelte/store';
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import NewLogModal from '$components/Modals/NewLogModal.svelte';

    interface Props {
        auth?: any;
    }

    let { auth = null }: Props = $props();

    const dropzones = writable(Array(4).fill(null));

    let newLogModalOpen = $state(false);

</script>

<AuthenticatedLayout data={ {auth} }>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard - {auth.user.name}</h2>


    <div class="py-12" ondragover={preventDefault(bubble('dragover'))} role="application" >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-5 sm:grid-cols-2 grid-cols-1">
            <DashboardItem>
                <Clock />
            </DashboardItem>
            <DashboardItem>
                <ClockingForm>
                    {#snippet indicator()}
                                        <ElapsedMarker />
                                    {/snippet}
                </ClockingForm>
            </DashboardItem>
            <DashboardItem>
                <PrimaryButton on:click={() => newLogModalOpen = true}>Manually add a log entry</PrimaryButton>
            </DashboardItem>
        </div>
    </div>

    <NewLogModal bind:open={newLogModalOpen} />
</AuthenticatedLayout>

