<script lang="ts">
    import dayjs from "dayjs";
    import localeData from "dayjs/plugin/localeData";
    import { leftPad } from "$lib/Text";
    import ActivityInlineReport from "$components/ActivityInlineReport.svelte";
    import route from "$vendor/tightenco/ziggy/src/js";
    import { _ } from 'lodash';
    import MiniButton from "./MiniButton.svelte";
    import { onMount } from "svelte";
    dayjs.extend(localeData);

    export let headers: string[] = [];
    export let data: any[] = [];
    export let date: Date;

    const latestDateWithLogs = Object.keys(data).sort().reverse()[0]
    let detailsOpen: boolean[] = Array.from({ length: 31 }, () => false);
    let container: HTMLDivElement;
    let latestNonEmptyRow: HTMLTableRowElement;
    let scrollY: number = 0;
    $: allOpen = detailsOpen.every(b => b);

    onMount(() => {
        scrollToLatest();
    });

    function scrollToLatest() {
        latestNonEmptyRow = document.getElementById(latestDateWithLogs) as HTMLTableRowElement;
        latestNonEmptyRow?.scrollIntoView({ behavior: 'smooth', block: 'center'});
    }

    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth'});
    }

    function toggleDetails(index: number) {
        detailsOpen[index] = !detailsOpen[index];
    }

    function toggleAllDetails() {
        detailsOpen = Array.from({ length: 31 }, () => !allOpen);
    }



    $: year = dayjs(date).format('YYYY');
    $: month = dayjs(date).format('MM');
    $: readableMonth = dayjs(date).format('MMMM');
    $: dateMap = Object.fromEntries(
        Array.from(
            { length: dayjs(date).daysInMonth() },
            (_, i) => {
                let day = i + 1;
                let date = `${year}-${month}-${leftPad(day,'0',2)}`;
                return [date, data[date]];
            }
        )
    );
    $: datesArray = Object.entries(dateMap);

</script>
<svelte:window bind:scrollY={scrollY} />

<head class="flex justify-between">
    <MiniButton color="ghost" href={route('activities.index',{date: dayjs(date).subtract(1,'month').format("YYYY-MM-DD")})}>
        Back
    </MiniButton>
    <h2 class="text-3xl w-full text-center">{readableMonth} {year}</h2>
    <MiniButton color="ghost" href={route('activities.index',{date: dayjs(date).add(1,'month').format("YYYY-MM-DD")})}>
        Next
    </MiniButton>
</head>
<div class="w-11/12 pb-10 mx-auto border rounded-md shadow-sm flex gap-2" bind:this={container}>
    <table class="table table-hover w-full table-md table-pin-rows table-pin-cols">
        <thead>
            <tr>
                <th>Date</th>
                <th class="flex items-center justify-between">
                    Activities
                    {#if scrollY > 125}
                        <div class="text-gray-400">{readableMonth} {year}</div>
                    {/if}
                    <div>
                        <MiniButton color="ghost" on:click={() => scrollToTop()}>To Top</MiniButton>
                        <MiniButton color="ghost" on:click={scrollToLatest}>See Latest</MiniButton>
                        <MiniButton color="ghost" on:click={toggleAllDetails}>{allOpen ? 'Close' : 'Open'} All</MiniButton>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
        {#if datesArray.some(([key, logs]) => logs?.length)}
            {#each datesArray as [key, logs], index}
                <tr id="{key}" class="hover">
                    <td>
                        <div class="text-gray-400">{ dayjs(key).date() } ({dayjs.weekdaysShort()[dayjs(key).day()]})</div>
                    </td>
                    <td>
                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 w-full">
                            {#if logs?.length}
                                {#each logs as log, logIndex}
                                    
                                    <ActivityInlineReport 
                                        id={`activity_${key.replaceAll('-','')}${index}${logIndex}`}  
                                        {log}
                                        detailsOpen={detailsOpen[index]}
                                    />

                                {/each}

                                <div class="flex items-center gap-1 self-center justify-self-end" style="grid-column: -1 / auto">
                                    <MiniButton color="teal" href={route('activities.show',{date: key})}>Edit</MiniButton>
                                    {#if detailsOpen[index]}
                                        <MiniButton color="white" on:click={() => toggleDetails(index)} >Hide</MiniButton>
                                    {:else}
                                        <MiniButton color="ghost" on:click={() => toggleDetails(index)} >Details</MiniButton>
                                    {/if}
                                </div>
                            {:else}
                                <MiniButton color="ghost" 
                                    href={route('activities.show',{date: key})}
                                    class="lg:col-start-3 md:col-start-2 place-self-end">
                                    Add
                                </MiniButton>
                            {/if}
                            
                            
                        </div>
                    </td>
                </tr>
            {/each}
        {:else}
            <tr>
                <td colspan="2">
                    <div class="text-center text-gray-400">No activities found for this month.</div>
                </td>
            </tr>
        {/if}
        </tbody>
    </table>
</div>