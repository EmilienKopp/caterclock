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
    import DangerButton from "$components/DangerButton.svelte";
    import { Lock, LockFill, PenFill, UnlockFill } from "svelte-bootstrap-icons";

    export let activities: any[];
    export let taskCategories: any[];
    export let log: any;

    let aboveMax: boolean = true;
    let loading: boolean = false;
    let safetyOn: boolean = true;

    $: form = useForm({
        date: log.date,
        project_id: log.project_id,
        activities: activities.filter((a: any) => a.project_id == log.project_id)
    })

    $: console.log($form);

    
    
    $: activitiesTotal = $form.activities
        .filter((a: any) => a.project_id == log.project_id)
        .reduce((a: number, b: any) => a + b.duration, 0);

    $: if(safetyOn && log?.total_seconds && Duration.flooredToMinute(activitiesTotal) > Duration.flooredToMinute(log.total_seconds)) {
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
        $form.activities = [
            ...$form.activities,
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
        loading = true;
        $form.post(route('activities.store'),{
            onSuccess: () => {
                toast.success('Activities saved successfully');
                $form.isDirty = false;
                loading = false;
            },
        });
    }

    function removeItem(index: number) {
        $form.activities = $form.activities.filter((a, i) => i != index);
    }

    function fill(index: any) {
        const remaining = log.total_seconds - activitiesTotal;
        console.log(remaining, log.total_seconds, activitiesTotal);
        $form.activities[index].duration = $form.activities[index].duration + remaining;
        console.log($form.activities[index].duration);
    }

    function clear(index: any) {
        $form.activities[index].duration = 0;
    }
</script>

<form class="rounded border p-5" on:submit|preventDefault={save}
    class:border-yellow-100={$form.isDirty && !aboveMax}
    class:border-red-600={aboveMax}
    class:border-green-600={!aboveMax && !$form.isDirty}
>
    <h2>
        {log.date} {log.project_name} ({Duration.toHHMM(log.total_seconds)})
        {#key safetyOn}
        <MiniButton class="text-xs" color="{safetyOn ? 'green' : 'yellow'}" on:click={() => safetyOn = !safetyOn}>
            Input safety: {safetyOn ? 'On' : 'Off'}
        </MiniButton>
        {/key}
    </h2>
    <table class="table">
        <thead>
            <tr>
                <th >Task Category</th>
                <th >Duration</th>
                <th >Actions</th>
            </tr>
        </thead>
        <tbody>
            {#each $form.activities as activity, index (activity.id)}
                <tr>
                    <td>
                        <Select name="activity_{activity.project_id}[{index}]" 
                            label="Task Category"
                            bind:value={activity.task_category_id}
                            items={taskCategories} mapping={{labelColumn: 'name', valueColumn: 'id'}} />
                    </td>
                    <td>
                        {#key $form.activities[index].duration}
                            <DurationInput bind:value={$form.activities[index].duration} max={log.total_seconds}
                                parentTotal={activitiesTotal} {safetyOn}
                                on:minutekeyup={(e) => handleKeyup(e, activity, index)} 
                                on:hourkeyup={(e) => handleKeyup(e, activity, index)}
                            />
                        {/key}
                    </td>
                    <td class="grid grid-flow-row gap-1 md:grid-cols-2 grid-cols-1">
                        <MiniButton color="yellow" on:click={() => clear(index)} title="Clear duration">
                            Clear
                        </MiniButton>
                        <MiniButton color="red" on:click={() => removeItem(index)}>
                            Remove
                        </MiniButton>
                        {#if Duration.flooredToMinute(activitiesTotal) < Duration.flooredToMinute(log.total_seconds)}
                            <MiniButton on:click={() => fill(index)} title="Use all remaining time">
                                Fill
                            </MiniButton>
                        {/if}
                    </td>
                </tr>
            {/each}
            <tr>
                <td colspan="3">
                    <SecondaryButton on:click={() => addRow(log.project_id)} disabled={$form.processing}>
                        Add Activity
                    </SecondaryButton>
                    <PrimaryButton 
                        title={aboveMax ? 'Total duration cannot be greater than ' + Duration.toHHMM(log.total_seconds) : 'Save activities'}
                        loading={$form.processing}
                        disabled={aboveMax || activitiesTotal == 0 || activities.some(a => !a.task_category_id)} >
                            Save
                    </PrimaryButton>
                    
                </td>
            </tr>
        </tbody>
        
    </table>
    
</form>
