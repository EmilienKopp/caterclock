<script lang="ts">
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import Select from '$components/Inputs/Select.svelte';
    import { useForm, page } from '@inertiajs/svelte';
    import route from '$vendor/tightenco/ziggy';
    import { latestClockInTime, queryParams } from '$lib/stores';
    import { setContext } from 'svelte';
    import dayjs from 'dayjs';

    
    const {entries,projects,projectDurations} = $page.props;

    let running = entries.find((entry: any) => entry?.out_time == null);

    setContext('running', running);

    $: $latestClockInTime = Date.parse(running?.in_time);
    $: console.log($queryParams);

    let action: "in" | "out" = "in";

    const {user} = $page.props.auth;

    const form = useForm({
        user_id: user.id,
        project_id: entries[0]?.project_id,
        date: null,
        in_project_id: null,
        out_project_id: null,
    })

    function clock() {
        $form.post(route('timelog.store'));
        if(action == "in") {
            $latestClockInTime = Date.now();
        }
    }

    $: if(!entries.length || !entries.some((entry: any) => entry.out_time == null) ) {
        action = "in";
    } else if (entries.some((entry: any) => entry.out_time == null)) {
        action = "out";
    }
    $: switchingProjects = (action == "out" && entries[0]?.project_id != $form.project_id)

</script>

<form method="POST" on:submit|preventDefault={clock} class="grid grid-cols-2 gap-4 items-center">
    {#if switchingProjects}
        <PrimaryButton disabled={!$form.project_id} type="submit">Switch Project</PrimaryButton>
    {:else}
        <PrimaryButton disabled={!$form.project_id}  type="submit">Clock {action}</PrimaryButton>
    {/if}

    <Select name="project_id" bind:value={$form.project_id} 
            items={projects} mapping={{labelColumn: 'name', valueColumn: 'id'}} />

    <div class="col-span-2">
        <slot name="indicator" {running} />
    </div>
</form>

