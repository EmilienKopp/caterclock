<script lang="ts">
    import DailyLogInputForm from './DailyLogInputForm.svelte';
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import { Input } from "flowbite-svelte";
    import { toast } from "$lib/stores";
    import { router, useForm, page} from '@inertiajs/svelte';
    import route from '$vendor/tightenco/ziggy';
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import WarningButton from '$components/Buttons/WarningButton.svelte';
    import OutlineButton from '$components/Buttons/OutlineButton.svelte';
    import dayjs from 'dayjs';
    import type { Activity, DailyLog, TaskCategory } from '$models';
    import MiniButton from '$components/Buttons/MiniButton.svelte';
    import NewLogModal from '$components/Modals/NewLogModal.svelte';


    export let activities: Activity[];
    export let dailyLogs: DailyLog[];
    export let taskCategories: TaskCategory[];
    export let date: string;

    let selectedDate = dayjs(date).format('YYYY-MM-DD');
    let logModalOpen = false;

    let form = useForm({
        date: selectedDate,
        activities: activities
    })

    function handleDateSelection() {
        if(!selectedDate) return;
        router.get(route('activities.show', { date: selectedDate }));
    }

    async function saveAll() {
        $form.post(route('activities.store'), {
            onSuccess: () => {
                toast.success('Activities saved successfully.');
            },
            onError: () => {
                toast.error('Error saving activities.');
                console.log($form);
            }
        });
    }

    async function saveAllAndReturn() {
        $form.post(route('activities.store'), {
            onSuccess: () => {
                toast.success('Activities saved successfully.');
                router.get(route('activities.index'));
            },
            onError: () => {
                toast.error('Error saving activities.');
            }
        });
    }

    function showLogModal() {
        logModalOpen = true;
    }

    $: $form.activities = dailyLogs.flatMap((log) => {
        return log.activities;
    });

</script>

<AuthenticatedLayout>
    <h1 class="text-2xl font-semibold">Daily Logs</h1>
    <Input type="date" class="w-44 dark:text-white" on:change={handleDateSelection} bind:value={selectedDate}/>
    
    <div class="grid 2xl:grid-cols-2 grid-cols-1 gap-4 my-3">
        {#if dailyLogs.length == 0}
            <div class="col-span-2">
                
                <p>No logs found for this date.</p>
                <MiniButton type="button" color="info" on:click={showLogModal}>
                    Create a new log
                </MiniButton>
            </div>
            

        {:else}

            {#each dailyLogs as log}
                {#if log.activities}
                    <DailyLogInputForm bind:log {taskCategories} />
                {/if}
            {/each}
            <div class="2xl:col-span-2 col-span-1">
                <PrimaryButton on:click={saveAll} loading={$form.processing}>Save All</PrimaryButton>
                <PrimaryButton on:click={saveAllAndReturn} loading={$form.processing}>Save All and Go Back</PrimaryButton>
                <OutlineButton href={route("activities.index")}>
                    {#if $form.isDirty}
                        Discard Changes
                    {:else}
                        Go Back
                    {/if}
                </OutlineButton>
            </div>
        {/if}
    </div>
    
</AuthenticatedLayout>
<NewLogModal bind:open={logModalOpen} />