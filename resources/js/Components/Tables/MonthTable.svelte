<script lang="ts">
    import ActivityInlineReport from "$components/App/Activity/ActivityInlineReport.svelte";
    import MiniButton from "$components/Buttons/MiniButton.svelte";
    import { leftPad } from "$lib/utils/text";
    import route from "$vendor/tightenco/ziggy/src/js";
    import { router } from '@inertiajs/svelte';
    import dayjs from "dayjs";
    import localeData from "dayjs/plugin/localeData";
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

    function handleContextMenu(event: MouseEvent) {
        console.log(event);
        const X = event.clientX;
        const Y = event.clientY;
        console.log(X, Y);
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

<header class="flex justify-between">
    <MiniButton color="ghost" href={route('activities.index',{date: dayjs(date).subtract(1,'month').format("YYYY-MM-DD")})}>
        Back
    </MiniButton>
    <h2 class="text-3xl w-full text-center">{readableMonth} {year}</h2>
    <MiniButton color="ghost" href={route('activities.index',{date: dayjs(date).add(1,'month').format("YYYY-MM-DD")})}>
        Next
    </MiniButton>
</header>
<div class="w-11/12 pb-10 mx-auto border rounded-md shadow-sm flex gap-2" bind:this={container}>
    <table class="table table-hover w-full table-xs table-pin-rows table-pin-cols" >
        <thead class="z-50">
            <tr>
                <th>Date</th>
                <th class="flex items-center justify-between">
                    Activities
                    {#if scrollY > 125}
                        <div class="text-gray-400">{readableMonth} {year}</div>
                    {/if}
                    <div>
                        <MiniButton color="info" on:click={() => scrollToTop()}>To Top</MiniButton>
                        <MiniButton color="info" on:click={scrollToLatest}>See Latest</MiniButton>
                        <MiniButton color="info" on:click={toggleAllDetails}>{allOpen ? 'Close' : 'Open'} All</MiniButton>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>

            {#each datesArray as [key, logs], index}
                <tr id="{key}" class="hover" on:contextmenu|preventDefault={handleContextMenu} >
                    <td>
                        <div class="text-gray-400 text-center">
                            { dayjs(key).date() } <br />
                            ({dayjs.weekdaysShort()[dayjs(key).day()]})
                        </div>
                    </td>
                    {#if logs?.length}
                    <td>
                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 w-full h-full">

                                {#each logs as log, logIndex}
                                    
                                    <ActivityInlineReport 
                                        id={`activity_${key.replaceAll('-','')}${index}${logIndex}`}  
                                        {log}
                                        detailsOpen={detailsOpen[index]}
                                    />

                                {/each}

                                <div class="flex items-center gap-1 self-center justify-self-end" style="grid-column: -1 / auto">
                                    <MiniButton color="primary" href={route('activities.show',{date: key})}>Edit</MiniButton>
                                    {#if detailsOpen[index]}
                                        <MiniButton color="info" on:click={() => toggleDetails(index)} >Hide</MiniButton>
                                    {:else}
                                        <MiniButton color="info" on:click={() => toggleDetails(index)} >Details</MiniButton>
                                    {/if}
                                </div>
                        </div>
                    </td>
                    {:else}
                    <td on:click={() => router.visit(route('activities.show',{date: key}))} class="cursor-pointer">
                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 w-full h-full">
                            <MiniButton color="ghost" class="col-start-3 place-self-end"> Add </MiniButton>
                        </div>
                    </td>
                    {/if}
                </tr>
            {/each}

        </tbody>
    </table>
</div>