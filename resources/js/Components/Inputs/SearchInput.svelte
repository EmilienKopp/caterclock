<script lang="ts">
    import MiniButton from '$components/Buttons/MiniButton.svelte';
    import { FilterService } from '$lib/Filter';
    import { resolveNestedValue } from '$lib/Objects';
    import { createEventDispatcher } from 'svelte';
    import InputLabel from './InputLabel.svelte';

    export let searchString = '';
    export let items: any[] = [];
    export let key: string = 'name';

    const dispatch = createEventDispatcher();
</script>

<div class="h-full flex flex-col gap-3">
    <div class="flex flex-col">
        <InputLabel for="name" >Search</InputLabel>
        <input type="text" id="name" bind:value={searchString} />
    </div>
    <ul>
        {#each items.filter( (item) => FilterService.Fuzzy(resolveNestedValue(item,key),searchString)) ?? [] as item}
            <li class="p-3 my-2 text-primary text-lg flex justify-between w-full">
                <span>
                    {@html FilterService.fuzzyHighlight(resolveNestedValue(item,key), searchString)}
                </span>

                <MiniButton on:click={() => dispatch('chose',{item})} >
                    Choose
                </MiniButton>
            </li>
        {/each}
    </ul>
</div>