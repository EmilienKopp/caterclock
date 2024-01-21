<script lang="ts">
    import DailyLogInputForm from './DailyLogInputForm.svelte';
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import { Input } from "flowbite-svelte";
    import { toast } from "$lib/stores";
    import { router, useForm } from '@inertiajs/svelte';
    import route from '$vendor/tightenco/ziggy';
    import PrimaryButton from '$components/PrimaryButton.svelte';
    import SecondaryButton from '$components/SecondaryButton.svelte';
    import DangerButton from '$components/DangerButton.svelte';

    export let activities: any[];
    export let dailyLogs: any[];
    export let taskCategories: any[];

    $: selectedDate = new Date().toISOString().split('T')[0];
    $: form = useForm({
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

</script>

<AuthenticatedLayout>
    <h1 class="text-2xl font-semibold">Daily Logs</h1>
    <Input type="date" class="w-44" on:change={handleDateSelection} bind:value={selectedDate}/>
    
    <div class="grid 2xl:grid-cols-2 grid-cols-1 gap-4 my-3">
        {#if dailyLogs.length == 0}
            <p class="col-span-2">No daily logs found for this date.</p>
        {/if}
        
        {#each dailyLogs as log}
            {#if activities}
            <DailyLogInputForm {log} {taskCategories} bind:activities={$form.activities}/>
            {/if}
        {/each}
    </div>
    <PrimaryButton on:click={saveAll} loading={$form.processing}>Save All</PrimaryButton>
    <PrimaryButton on:click={saveAllAndReturn} loading={$form.processing}>Save All and Go Back</PrimaryButton>
</AuthenticatedLayout>
