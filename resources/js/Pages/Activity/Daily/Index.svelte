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


    export let activities: any[];
    export let dailyLogs: any[];
    export let taskCategories: any[];
    export let date: string;

    let selectedDate = dayjs(date).format('YYYY-MM-DD');


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

    $: $form.activities = dailyLogs.flatMap((log) => {
        return log.activities;
    });

</script>

<AuthenticatedLayout>
    <h1 class="text-2xl font-semibold">Daily Logs</h1>
    <Input type="date" class="w-44" on:change={handleDateSelection} bind:value={selectedDate}/>
    
    <div class="grid 2xl:grid-cols-2 grid-cols-1 gap-4 my-3">
        {#if dailyLogs.length == 0}
            <p class="col-span-2">
                No daily logs found for this date.
                
            </p>
        {/if}
        
        {#each dailyLogs as log}
            {#if log.activities}
                <DailyLogInputForm bind:log {taskCategories} />
            {/if}
        {/each}
    </div>
    <PrimaryButton on:click={saveAll} loading={$form.processing}>Save All</PrimaryButton>
    <OutlineButton on:click={saveAllAndReturn} loading={$form.processing}>Save All and Go Back</OutlineButton>
    <WarningButton href={route("activities.index")} class="mx-12">Cancel</WarningButton>
</AuthenticatedLayout>
