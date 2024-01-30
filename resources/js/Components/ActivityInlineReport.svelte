<script context="module" lang="ts">
    export let openPopoverId: string;
</script>
<script lang="ts">
    import ActivityPieChart from './ActivityPieChart.svelte';
    import ActivityLogItem from './ActivityLogItem.svelte';
    import { Duration } from "$lib/Duration";
    import type { Activity } from "$types";
    import Dialog from './Dialog.svelte';
    import MiniPie from './MiniPie.svelte';
    import { Popover } from 'flowbite-svelte';
    import { slide } from 'svelte/transition';
    import { InfoCircleFill, PencilSquare } from 'svelte-bootstrap-icons';
    import route from '$vendor/tightenco/ziggy/src/js';

    export let log: any;
    export let detailsOpen: boolean = false;
    export let id : string;

    let open: boolean = false;
    let projectName: string = log?.project_name;

    // Ensures popovers are only ever open one at a time, and that they close when opening the dialog
    $: popoverOpen = open && openPopoverId === id;
    function handleClick() {
        open = !open;
        openPopoverId = open ? id : '';
    }
</script>

<div class="mx-3 p-3 rounded bg-transparent">
    <h3 class="text-lg font-semibold flex items-center gap-2">
        
        {projectName}
        <div class="badge md:text-md text-xs whitespace-nowrap">
            {Duration.toHrMinString(log.total_seconds)}
        </div>
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