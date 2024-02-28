<script lang="ts">
  import PageWideLoadScreen from './PageWideLoadScreen.svelte';

    import Select from "$components/Inputs/Select.svelte";
    import { Duration } from "$lib/Duration";
    import DurationInput from "$components/Inputs/DurationInput.svelte";
    import { toast, user } from "$lib/stores";
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import { router, useForm } from '@inertiajs/svelte';
    import route from '$vendor/tightenco/ziggy';
    import MiniButton from "$components/Buttons/MiniButton.svelte";
    import OutlineButton from "$components/Buttons/OutlineButton.svelte";
    import Dialog from "$components/Modals/Dialog.svelte";
    import dayjs from "dayjs";
    import type { TaskCategory } from "$models";
    import { Question, QuestionCircleFill, ShieldCheck, ShieldFillCheck, ShieldFillX, ThreeDots } from "svelte-bootstrap-icons";
    import { fade } from 'svelte/transition';


    export let taskCategories: TaskCategory[];
    export let log: any;

    const logEntry = log.timeLogs[0];
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
        console.log($form);
        $form.post(route('activities.store'),{
            onSuccess: () => {
                toast.success('Activities saved successfully');
                $form.isDirty = false;
                loading = false;
            },
            onError: (error: any) => {
                toast.error('Error saving activities');
                console.log(error);
            }
        });
    }

    async function handleDelete() {
        console.log($form);
        if(confirm('Are you sure you want to delete this log?')) {
            $form.delete(route('timelog.destroy', {timelog:logEntry.id}), {
                onStart: () => {
                    toast.info('Deleting log...');
                },
                onSuccess: () => {
                    toast.success('Log deleted successfully');
                },
                onError: () => {
                    toast.error('Error deleting log');
                }
            });
        }
    }

    function removeItem(index: number) {
        $form.activities = $form.activities.filter((a, i) => i != index);
    }

    function fill(index: any) {
        const remaining = log.total_seconds - activitiesTotal;
        $form.activities[index].duration = Math.round($form.activities[index].duration + remaining);
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

<form class="rounded border p-5" on:submit|preventDefault={save} transition:fade
    class:border-green-600={!$form.isDirty}
    class:border-yellow-100={$form.isDirty && !aboveMax}
    class:border-red-600={aboveMax}
>
    {#if $form.processing}
        <div class="w-full h-full opacity-70 flex items-center justify-center">
            <span class="loading loading-dots loading-lg"></span>
        </div>
    {:else}
    <div class="flex justify-between text-lg">
        <h2>
            {log.date}・{log.project_name}・({Duration.toHrMinString(log.total_seconds)})
            <span class="tooltip" data-tip="Safety mode prevents you from saving activities that exceed the total duration of the log.">
                <button type="button" class="text-xs" 
                        class:text-red-500={!safetyOn} class:text-green-400={safetyOn} 
                        on:click={() => safetyOn = !safetyOn} 
                >
                    {#if safetyOn}
                        <ShieldFillCheck />
                    {:else}
                        <ShieldFillX />
                    {/if}
                </button>
            </span>
        </h2>


        <div>
            <MiniButton class="text-xs mx-4" on:click={() => clockEntryModalOpen = true}>Edit clock entries</MiniButton>
            <MiniButton class="text-xs" on:click={handleDelete} color="warning">Delete</MiniButton>
        </div>

    </div>
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
                    <td>
                        <div class="dropdown ">
                            <div tabindex="0" role="button" class="btn m-1 btn-ghost btn-outline"><ThreeDots/>Options</div>
                            <ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52">
                                {#if Duration.flooredToMinute(activitiesTotal) < Duration.flooredToMinute(log.total_seconds)}
                                    <li><button type="button" on:click={() => fill(index)}>Fill</button></li>
                                {/if}
                                <li><button type="button" on:click={() => clear(index)}>Clear</button></li>
                                <li><button type="button" class="text-red-300" on:click={() => removeItem(index)}>Remove</button></li>
                                <li><button type="button" on:click={$form.reset()}>Reset</button></li>
                            </ul>
                        </div>
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
    {/if}
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

