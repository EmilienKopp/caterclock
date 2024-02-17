<script lang="ts">
    import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
    import Clock from '$components/App/TimeLog/Clock.svelte';
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import Select from '$components/Inputs/Select.svelte';
    import TimeLogTable from '$components/App/TimeLog/TimeLogTable.svelte';
    import { router, useForm, page } from '@inertiajs/svelte';
    import route from '$vendor/tightenco/ziggy';
    import { latestClockInTime, elapsedHours, elapsedMinutes, elapsedSeconds } from '$lib/stores';
    import { Duration } from '$lib/Duration';

    export let entries: any[];
    export let projects: any[];
    export let projectDurations: any[];

    let running = entries.find(entry => entry?.out_time == null);
    $: $latestClockInTime = Date.parse(running?.in_time);

    let action: "in" | "out" = "in";

    const {user} = $page.props.auth;

    const form = useForm({
        user_id: user.id,
        project_id: null,
        date: null,
        in_project_id: null,
        out_project_id: null,
    })

    function clock() {
        $form.post(route('timelog.store'));
    }

    $: if(!entries.length || entries[0]?.out_time != null) {
        action = "in";
    } else if (entries[0]?.out_time == null) {
        action = "out";
    }
    $: switchingProjects = (action == "out" && entries[0]?.project_id != $form.project_id)

    $: console.log(projectDurations);
</script>

<AuthenticatedLayout>
    <div class="flex flex-col justify-center items-center">

        <div class="flex flex-col items-center justify-around w-full border rounded-sm p-5">
            <Clock />
            <form method="POST" on:submit|preventDefault={clock} class="grid grid-cols-2 gap-4 items-center">
                {#if switchingProjects}
                    <PrimaryButton disabled={!$form.project_id} type="submit">Switch Project</PrimaryButton>
                {:else}
                    <PrimaryButton disabled={!$form.project_id}  type="submit">Clock {action}</PrimaryButton>
                {/if}

                <Select size="xs" name="project_id" placeholder="プロジェクト選択" bind:value={$form.project_id} 
                        items={projects} mapping={{labelColumn: 'name', valueColumn: 'id'}} />

            </form>
        </div>
        
        <TimeLogTable {entries} />

    </div>
</AuthenticatedLayout>

