<script lang="ts">
    import { createBubbler } from 'svelte/legacy';

    const bubble = createBubbler();
    import { twMerge } from 'tailwind-merge';

    interface Props {
        value?: string | number | null;
        items?: {label:string,value: number|string}[];
        name: string;
        mapping?: { labelColumn: string, valueColumn: string };
        children?: import('svelte').Snippet;
        [key: string]: any
    }

    let {
        value = $bindable(null),
        items = [],
        name,
        mapping = { labelColumn: "label", valueColumn: "value" },
        children,
        ...rest
    }: Props = $props();


    let options: {label: string, value: string | number}[] = items?.map((item: any) => {
        return {
            label: item[mapping.labelColumn],
            value: item[mapping.valueColumn]
        }
    });

</script>



<select bind:value {name} {...rest}  class="select select-bordered w-full max-w-xs"
    onchange={bubble('change')}
    onclick={bubble('click')}
    oninput={bubble('input')}
    onblur={bubble('blur')}
    onfocus={bubble('focus')}
>
    {#if options?.length > 0}
        {#each options ?? [] as item, index}
            <option value={item.value}>{item.label}</option>
        {/each}
    {:else}
        {@render children?.()}
    {/if}
</select>
