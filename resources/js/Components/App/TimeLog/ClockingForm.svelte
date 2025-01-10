<script lang="ts">
    import { preventDefault } from 'svelte/legacy';

    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
    import Select from '$components/Inputs/Select.svelte';
    import route from '$vendor/tightenco/ziggy';
    import { page, useForm } from '@inertiajs/svelte';
    import { setContext } from 'svelte';

    interface Props {
        indicator?: import('svelte').Snippet<[any]>;
    }

    let { indicator }: Props = $props();
    const { auth: {user}, entries, projects} = $page.props;
    const form = useForm({
        user_id: user.id,
        project_id: entries[0]?.project_id,
        timestamp: null,
        in_project_id: null,
        out_project_id: null,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
    })

    let running = $derived(entries?.find((entry: any) => entry?.out_time == null));
    let latestClockInTime = $state(entries?.[0]?.in_time);
    let action = $state<"in" | "out">("in");
    let switchingProjects = ($derived(action == "out" && entries?.[0]?.project_id != $form.project_id))


    $effect.pre(() => {
        setContext('running', running );
        if(entries && (!entries?.length || !entries?.some((entry: any) => entry.out_time == null)) ) {
            action = "in";
        } else if (entries?.some((entry: any) => entry?.out_time == null)) {
            action = "out";
        }
    });
    
    function clock() {
        if(action == "in" || switchingProjects) {
            latestClockInTime = Date.now();
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

