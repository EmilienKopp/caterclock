<script lang="ts">
    import Select from "$components/Select.svelte";
    import { Duration } from "$lib/Duration";
    import DurationInput from "$components/DurationInput.svelte";
    import { toast, user } from "$lib/stores";
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import SecondaryButton from "$components/Buttons/SecondaryButton.svelte";
    import { router, useForm } from '@inertiajs/svelte';
    import route from '$vendor/tightenco/ziggy';
    import MiniButton from "$components/MiniButton.svelte";
    import { Lock, LockFill, PenFill, UnlockFill } from "svelte-bootstrap-icons";
    import OutlineButton from "$components/Buttons/OutlineButton.svelte";
    import WarningButton from "$components/Buttons/WarningButton.svelte";
    import Dialog from "$components/Dialog.svelte";
    import dayjs from "dayjs";


    export let taskCategories: any[];
    export let log: any;

    let aboveMax: boolean = true;
    let loading: boolean = false;
    let safetyOn: boolean = true;
    let clockEntryModalOpen = false;

    const clockEntriesForm = useForm({
        entries: log.timeLogs.map((entry: any) => {
            return {
                ...entry,
                in_time: dayjs(entry.in_time).format('YYYY-MM-DDTHH:mm'),
                out_time: entry.out_time ? dayjs(entry.out_time).format('YYYY-MM-DDTHH:mm') : ''
            }
        })
    });

    const form = useForm({
        date: log.date,
        project_id: log.project_id,
        activities: log.activities.filter((a: any) => a.project_id == log.project_id)
    });
    
    $: activitiesTotal = $form.activities
        .filter((a: any) => a.project_id == log.project_id)
        .reduce((a: number, b: any) => a + b.duration, 0);

    $: if(safetyOn && log?.total_seconds && Duration.flooredToMinute(activitiesTotal) > Duration.flooredToMinute(log.total_seconds)) {
        toast.show('Total duration cannot be greater than ' + Duration.toHHMM(log.total_seconds), 'error');
        aboveMax = true;
    } else {
        aboveMax = false;
    }

    function handleKeydown(e: CustomEvent, activity: any, index: number) {
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
        log.activities = $form.activities;
        console.log(log.activities);
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

    async function updateClockEntries() {
        $clockEntriesForm.put(route('timelog.batch-update'), {
            onSuccess: () => {
                toast.success('Clock entries updated successfully');
                clockEntryModalOpen = false;
            },
            onError: () => {
                toast.error('Error updating clock entries');
                console.log($clockEntriesForm.errors);
            }
        });
    }

    $: log.activities = $form.activities;
</script>

<form class="rounded border p-5" on:submit|preventDefault={save}
    class:border-yellow-100={$form.isDirty && !aboveMax}
    class:border-red-600={aboveMax}
    class:border-green-600={!aboveMax && !$form.isDirty}
>
    <h2 class="flex justify-between text-lg">
        <div>
            {log.date}・{log.project_name}・({Duration.toHrMinString(log.total_seconds)})
            <MiniButton class="text-xs mx-4" on:click={() => clockEntryModalOpen = true}>Edit clock entries</MiniButton>
        </div>
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
            {#each $form.activities as activity, index}
                <tr>
                    <td>
                        <Select name="activity_{activity.project_id}[{index}]" 
                            label="Task Category"
                            bind:value={activity.task_category_id}
                            items={taskCategories} mapping={{labelColumn: 'name', valueColumn: 'id'}} />
                    </td>
                    <td>

                            <DurationInput bind:activity max={log.total_seconds}
                                parentTotal={activitiesTotal} {safetyOn}
                                on:minutekeydown={(e) => handleKeydown(e, activity, index)} 
                                on:hourkeydown={(e) => handleKeydown(e, activity, index)}
                            />
                        
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
                    <OutlineButton on:click={() => addRow(log.project_id)} disabled={$form.processing}>
                        Add Activity
                    </OutlineButton>
                    {#if $form.isDirty}
                        <PrimaryButton type="submit"
                            title={aboveMax ? 'Total duration cannot be greater than ' + Duration.toHHMM(log.total_seconds) : 'Save activities'}
                            loading={$form.processing}
                            disabled={aboveMax || activitiesTotal == 0 || log.activities.some(a => !a.task_category_id)} >
                                Save
                        </PrimaryButton>
                    {/if}
                </td>
            </tr>
        </tbody>
    </table>
</form>

<Dialog title="Clock entries" bind:open={clockEntryModalOpen} >

    {#each $clockEntriesForm.entries as entry,index}
        <div>
            <input bind:value={entry.in_time} class="rounded bg-transparent" 
                type="datetime-local" />
            ~
            <input bind:value={entry.out_time}
                class="rounded bg-transparent" type="datetime-local" />
        </div>
    {/each}

    <div slot="buttons">
        <PrimaryButton on:click={updateClockEntries}>Save</PrimaryButton>
    </div>

</Dialog>

