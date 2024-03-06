<script lang="ts">
    import { twMerge } from 'tailwind-merge';

    export let value: string | number | null = null;
    export let items: {label:string,value: number|string}[]  = [];
    export let name: string;
    export let mapping: { labelColumn: string, valueColumn: string } = { labelColumn: "label", valueColumn: "value" };


    let options: {label: string, value: string | number}[] = items?.map((item: any) => {
        return {
            label: item[mapping.labelColumn],
            value: item[mapping.valueColumn]
        }
    });

</script>



<select bind:value {name} {...$$restProps}  class="select select-bordered w-full max-w-xs"
    on:change
    on:click
    on:input
    on:blur
    on:focus
>
    {#if options?.length > 0}
        {#each options ?? [] as item, index}
            <option value={item.value}>{item.label}</option>
        {/each}
    {:else}
        <slot />
    {/if}
</select>
