<script lang="ts">
    import MiniButton from '$components/Buttons/MiniButton.svelte';
    import { FilterService } from '$lib/utils/filter';
    import { resolveNestedValue } from '$lib/utils/objects';
    import { createEventDispatcher } from 'svelte';
    import InputLabel from './InputLabel.svelte';

    interface Props {
        searchString?: string;
        items?: any[];
        key?: string;
    }

    let { searchString = $bindable(''), items = [], key = 'name' }: Props = $props();

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