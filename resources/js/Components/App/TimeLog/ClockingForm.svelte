<script lang="ts">
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import Select from '$components/Inputs/Select.svelte';
    import { latestClockInTime, toast } from '$lib/stores';
    import route from '$vendor/tightenco/ziggy';
    import { page, useForm } from '@inertiajs/svelte';
    import { setContext } from 'svelte';

    let entries: any[] = [], projects: any[] = [], projectDurations: any[] = [];

    $: ({entries,projects,projectDurations} = $page.props);
    $: running = entries?.find((entry: any) => entry?.out_time == null);
    $: projectName = projects?.find((project: any) => project.id == $form.project_id)?.name;
    $: $latestClockInTime = Date.parse(running?.in_time);
    $: setContext('running', running );

    let action: "in" | "out" = "in";

    const {user} = $page.props.auth;

    const form = useForm({
        user_id: user.id,
        project_id: entries[0]?.project_id,
        timestamp: null,
        in_project_id: null,
        out_project_id: null,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
    })

    function clock() {
        if(action == "in" || switchingProjects) {
            $latestClockInTime = Date.now();
        }

        $form.post(route('timelog.store'), {
            onError: () => {
                toast.error('An error occurred while trying to clock in/out');
                console.log($form.errors);
            },
            onSuccess: () => {
                $form.reset();
            }
        });
    }
    

    $: if(entries && (!entries?.length || !entries?.some((entry: any) => entry.out_time == null)) ) {
        action = "in";
    } else if (entries?.some((entry: any) => entry?.out_time == null)) {
        action = "out";
    }
    $: switchingProjects = (action == "out" && entries?.[0]?.project_id != $form.project_id)

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

