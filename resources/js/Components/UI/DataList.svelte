<script lang="ts">
    import type { DataListDefinition } from "$types";
    import { isEmpty } from "lodash";
    import { resolveNestedValue } from "supakit-eloquent/lib/objects";

    export let title: string = "";
    export let definition: DataListDefinition; 
    export let data: {[key:string]: any};
</script>

{#if title}
    <div class="flex w-full justify-between border-b border-gray-300">
        <h3 class="mt-5 ml-5 font-semibold text-xl">{title}</h3>
            <slot name="button" />
    </div>
{/if}

<dl>
    {#each Object.keys(definition) as key}
    {@const value = resolveNestedValue(data, key)}
        <dt>{definition[key].label}</dt>
        <dd>
        {#if Array.isArray(value) && value.length > 0}
            {#each value.filter(Boolean) as item}
                {item}
            {/each}
        {:else if typeof value === "boolean"}
            {#if value}
                はい
            {:else}
                いいえ
            {/if}
        {:else if typeof value === "object" && !isEmpty(value)}
            {#each Object.keys(value) as subKey}
                {value[subKey]}
            {/each}
        {:else if typeof value === "number"}
            {value.toLocaleString()}
        {:else if typeof value === "string"}
            {definition[key].formatter
                ? definition[key].formatter?.(value)
                : value ?? "-"}
        {:else}
            -
        {/if}
        </dd>
    {/each}
</dl>

<style>
    dl {
        @apply mt-6 ml-8 grid grid-cols-3 gap-y-6;
    }

    dd {
        @apply text-lg col-span-2;
    }

    dt {
        @apply text-lg text-gray-500;
    }
</style>