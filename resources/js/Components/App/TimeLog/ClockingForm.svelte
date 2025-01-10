<script lang="ts">
    import { preventDefault, run } from 'svelte/legacy';

    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
    import Select from '$components/Inputs/Select.svelte';
    import { latestClockInTime } from '$lib/stores.svelte';
    import route from '$vendor/tightenco/ziggy';
    import { useForm } from '@inertiajs/svelte';
    import { User } from 'lucide-svelte';
    import { setContext } from 'svelte';

    interface Props {
        indicator?: import('svelte').Snippet<[any]>;
        entries: any[];
        projects: any[];
        projectDurations: any[];
        auth: { user: User };
    }

    let { indicator, auth }: Props = $props();
    let user = auth.user;

    let entries: any[] = $state([]), projects: any[] = $state([]), projectDurations: any[] = $state([]);
    let running = $derived(entries?.find((entry: any) => entry?.out_time == null));
    let projectName = $derived(projects?.find((project: any) => project.id == $form.project_id)?.name);
    run(() => {
        latestClockInTime.set(Date.parse(running?.in_time))
    });
    run(() => {
        setContext('running', running );
    });

    let action: "in" | "out" = $state("in");


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
                toaster.error('An error occurred while trying to clock in/out');
                console.log($form.errors);
            },
            onSuccess: () => {
                $form.reset();
            }
        });
    }
    

    run(() => {
        if(entries && (!entries?.length || !entries?.some((entry: any) => entry.out_time == null)) ) {
            action = "in";
        } else if (entries?.some((entry: any) => entry?.out_time == null)) {
            action = "out";
        }
    });
    let switchingProjects = ($derived(action == "out" && entries?.[0]?.project_id != $form.project_id))

</script>

<form method="POST" onsubmit={preventDefault(clock)} class="grid grid-cols-2 gap-4 items-center">
    {#if switchingProjects}
        <PrimaryButton disabled={!$form.project_id} type="submit">Switch Project</PrimaryButton>
    {:else}
        <PrimaryButton disabled={!$form.project_id}  type="submit">Clock {action}</PrimaryButton>
    {/if}

    <Select name="project_id" bind:value={$form.project_id} 
            items={projects} mapping={{labelColumn: 'name', valueColumn: 'id'}} />

    
    <div class="col-span-2">
        {@render indicator?.({ running, })}
    </div>
</form>

