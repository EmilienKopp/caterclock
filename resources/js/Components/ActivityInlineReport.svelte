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


    export let activities: Activity[] = [];
    export let projectName: string = '';
    export let detailsOpen: boolean = false;
    export let id : string;

    $: console.log(detailsOpen);

    let open: boolean = false;
    
    const logTotal = activities?.[0]?.dailyLog?.total_seconds;

    // Ensures popovers are only ever open one at a time, and that they close when opening the dialog
    $: popoverOpen = open && openPopoverId === id;
    function handleClick() {
        open = !open;
        openPopoverId = open ? id : '';
    }
</script>

<div class="mx-3 p-3 rounded bg-transparent">
    <h3 class="text-lg font-semibold flex items-center gap-2">
        
        {projectName} <div class="badge md:text-md text-xs whitespace-nowrap">{logTotal ? Duration.toHrMinString(logTotal) : ""}</div>
        <button {id} class="ml-2" title="Click to enlarge" on:click={handleClick} on:mouseenter={() => openPopoverId = id}>
            <!-- <PieChartFill /> -->
            <MiniPie data={activities.map(a => a.duration)} />
        </button>
        <Popover bind:open={popoverOpen} trigger="hover" triggeredBy="#{id}">

                <ActivityPieChart {activities} width="200" height="200"/>

        </Popover>
    </h3>
    {#if detailsOpen}
        <div class="flex flex-col gap-1" transition:slide>
            {#each activities as activity}
                <ActivityLogItem {activity} />
            {/each}
        </div>
    {/if}
</div>

<Dialog bind:open title={projectName}>
    <ActivityPieChart {activities} />
</Dialog>