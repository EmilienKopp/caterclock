<script lang="ts">
    import Select from "$components/Select.svelte";
    import { Duration } from "$lib/Duration";
    import DurationInput from "$components/DurationInput.svelte";
    import { toast } from "$lib/stores";
    import PrimaryButton from "$components/PrimaryButton.svelte";
    import SecondaryButton from "$components/SecondaryButton.svelte";
    import { router, useForm } from '@inertiajs/svelte';
    import route from '$vendor/tightenco/ziggy';
    import MiniButton from "$components/MiniButton.svelte";

    export let activities: any[];
    export let taskCategories: any[];
    export let log: any;

    let aboveMax: boolean = false;
    const form = useForm({
        date: log.date,
        project_id: log.project_id,
        activities: activities
    })

    $: activitiesTotal = $form.activities
        .filter((a: any) => a.project_id == log.project_id)
        .reduce((a: number, b: any) => a + b.duration, 0);

    $: if(log?.total_seconds && Duration.flooredToMinute(activitiesTotal) > Duration.flooredToMinute(log.total_seconds)) {
        toast.show('Total duration cannot be greater than ' + Duration.toHHMM(log.total_seconds), 'error');
        aboveMax = true;
    } else {
        aboveMax = false;
    }
    
    


    function handleKeyup(e: CustomEvent, activity: any, index: number) {
        if(e.detail.key == 'Enter') {
            addRow(activity.project_id);
            if(document)
                document?.querySelector(`input[name="activity_${activity.project_id}[${index + 1}]"]`)?.focus();
        }
    }

    function addRow(projectId: number) {
        activities = [
            ...activities,
            {
                project_id: projectId,
                task_category_id: null,
                user_id: log.user_id,
                date: log.date,
                duration: 0
            }
        ]
    }

    async function save() {
        $form.activities = activities;
        $form.post(route('activities.store'),{
            onSuccess: () => {
                toast.success('Activities saved successfully');
                $form.isDirty = false;
            },
        });
    }

    function removeItem(index: number) {
        activities = activities.filter((a, i) => i != index);
    }

    function fill(index: any) {
        const remaining = log.total_seconds - activitiesTotal;
        console.log(remaining, log.total_seconds, activitiesTotal);
        $form.activities[index].duration += remaining;
        console.log(activities[index]);
    }

    function reset(index: any) {
        $form.activities[index].duration = 0;
    }
    
    $: console.log(activities,$form);
</script>

<form class="rounded border p-5" 
    class:border-yellow-100={$form.isDirty && !aboveMax}
    class:border-red-600={aboveMax}
    class:border-green-600={!aboveMax && !$form.isDirty}
>
    <h2>{log.date} {log.project_name} ({Duration.toHHMM(log.total_seconds)})</h2>
    <ul class="space-y-4">
        {#each $form.activities as activity, index (activity.id)}
            <li class="flex items-center gap-3">
                <button on:click={() => removeItem(index)} class="p-1">✖</button>
                <Select name="activity_{activity.project_id}[{index}]" bind:value={activity.task_category_id}
                    items={taskCategories} mapping={{labelColumn: 'name', valueColumn: 'id'}} />

                <DurationInput bind:value={$form.activities[index].duration} max={log.total_seconds} 
                    on:minutekeyup={(e) => handleKeyup(e, activity, index)} 
                    on:hourkeyup={(e) => handleKeyup(e, activity, index)}
                />

                <MiniButton color="yellow" on:click={() => reset(index)} title="Reset duration">
                    Clear
                </MiniButton>
                {#if Duration.flooredToMinute(activitiesTotal) < Duration.flooredToMinute(log.total_seconds)}
                    <MiniButton on:click={() => fill(index)} title="Use all remaining time">
                        Fill
                    </MiniButton>
                {/if}
            </li>
        {/each}
        <li>
            <SecondaryButton on:click={() => addRow(log.project_id)}>
                Add Activity
            </SecondaryButton>
            <PrimaryButton disabled={aboveMax || activitiesTotal == 0 || activities.some(a => !a.task_category_id)} on:click={save}>
                Save
            </PrimaryButton>
        </li>
    </ul>
    
</form>

