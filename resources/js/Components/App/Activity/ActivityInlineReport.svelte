<script context="module" lang="ts">
    export let openPopoverId: string;
</script>
<script lang="ts">
    import ActivityPieChart from '$components/Charts/ActivityPieChart.svelte';
    import ActivityLogItem from '$components/App/Activity/ActivityLogItem.svelte';
    import Select from '$components/Inputs/Select.svelte';
    import { Duration } from "$lib/Duration";
    import Dialog from '$components/Modals/Dialog.svelte';
    import MiniPie from '$components/Buttons/MiniPie.svelte';
    import { slide } from 'svelte/transition';
    import { InfoCircleFill, PencilSquare } from 'svelte-bootstrap-icons';
    import { useForm, page } from '@inertiajs/svelte';
    import { toast, user } from '$lib/stores';
    import route from '$vendor/tightenco/ziggy/src/js';
    import { onDestroy } from 'svelte';

    export let log: any;
    export let detailsOpen: boolean = false;
    export let id : string;

    let open: boolean = false;
    let projectName: string = log?.project_name;
    let elapsedSeconds: number = 0;
    const interval = setInterval(() => {
        elapsedSeconds++;
    }, 1000);


    const form = useForm({
        user_id: $user.id,
        project_id: log.project_id,
        date: null,
        in_project_id: null,
        out_project_id: null,
    })

    async function clock() {
        const logId = log.timeLogs.find( (l: any) => l.is_running)?.id;
        await $form.put(route('timelog.update',{timelog: logId}),{
            onSuccess: () => {
                toast.success('Clocked out successfully');
                log.is_running = false;
            }
        });
    }

    function handleClick() {
        open = !open;
        openPopoverId = open ? id : '';
    }

    let selectedLog: any = null;
    let fillModalOpen: boolean = false;
    let fillSteps = {

    }
    let selectedCategoryId: number = 0;

    function handleFill(log: any) {
        selectedLog = log;

    }

    onDestroy(() => {
        clearInterval(interval);
    });
</script>

<div class="mx-3 p-3 rounded bg-transparent">
    <h3 class="text-lg font-semibold flex items-center gap-2">
        
        {projectName}
        
        <form class="dropdown" on:submit|preventDefault={clock} >
            <input type="hidden" name="project_id" value={$form.project_id} />
            <button type="button" class="badge md:text-md text-xs whitespace-nowrap flex items-center" class:text-red-500={log.is_running} >
                {#if log.is_running}
                <span class="loading loading-infinity loading-xs mr-2"></span>
                {/if}
                {Duration.toHrMinString(Number(log.total_seconds) + elapsedSeconds)}
                
            </button>
            <ul class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                {#if log.is_running}
                    <li><button on:click={clock}>Clock Out</button></li>
                {/if}
                <li>

                    <button type="button" on:click={() => handleFill(log)}>Fill</button>
                    {#if fillModalOpen}
                        <Select name="activity" 
                            label="Task Category"
                            bind:value={selectedCategoryId}
                            items={$page.props.taskCategories} mapping={{labelColumn: 'name', valueColumn: 'id'}} />
                    {/if}
                </li>
                <li><a href={route('activities.show',{date: log.date})}>Edit</a></li>
            </ul>
        </form>
        {#if log.activities.length}
            <button {id} class="ml-2" title="Click to enlarge" on:click={handleClick} on:mouseenter={() => openPopoverId = id}>
                <!-- <PieChartFill /> -->
                <MiniPie data={log.activities.map(a => a.duration)} />
            </button>
            <!-- <Popover bind:open={popoverOpen} trigger="hover" triggeredBy="#{id}">
                <ActivityPieChart title={log.project_name} activities={log.activities} width="200" height="200"/>
            </Popover> -->
        {:else}
            <a href={route('activities.show',{date: log.date})}>
                <PencilSquare />
            </a>
        {/if}
    </h3>
    {#if detailsOpen}
        <div class="flex flex-col gap-1" transition:slide>
            {#each log.activities as activity}
                <ActivityLogItem {activity} />
            {/each}
        </div>
    {/if}
</div>


<Dialog bind:open title={projectName}>
    <ActivityPieChart title={log.project_name} activities={log.activities} />
</Dialog>