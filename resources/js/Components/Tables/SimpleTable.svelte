<script lang="ts" context="module">
    export type Header = {
        label: string;
        key: string;
        format?: (value: any) => string;
        transform?: (value: any) => any;
        href?: string;
        route?: string;
        asBadge?: boolean;
        popoverComponent?: any;
        popoverProp?: string;
    };

    export type PopoverList = {
        [key: string]: {
            component: any;
            prop: string;
        };
    }
</script>
<script lang="ts">
    import { resolveNestedValue } from "$lib/Objects";

    import route from "$vendor/tightenco/ziggy";
    import { Popover } from "flowbite-svelte";
    import { twMerge } from "tailwind-merge";

    export let data: any[] = [];
    export let title: string = "Companies";
    export let headers: Header[] = [
        { label: "Company", key: "name", route: "companies.show" },
    ];
    export let popovers: PopoverList = {};
    export let classes: { [key: string]: string } = {};
</script>

<table class="table table-hover w-full table-md">
    <thead>
        <tr>
            {#each headers as header}
                <th>{header.label}</th>
            {/each}
        </tr>
    </thead>
    <tbody>
        {#each data ?? [] as item}
        <tr class="p-3 my-2 text-white text-lg">
            {#each headers as header}
                {@const unformatted = resolveNestedValue(item, header.key) ?? ""}
                {@const transformed = header.transform ? header.transform(unformatted) : unformatted}
                {@const value = header.format ? header.format(transformed) : transformed}
                
                {@const uid = Math.random().toString(36).substring(7)}
                <td>
                    <span class={twMerge(popovers[header.key] ? "cursor-pointer" : "", classes[header.key])}>
                        {#if header.route}
                            <a href={route(header.route, item.id)}>{value}</a>
                        {:else if header.asBadge}
                            <span class="badge">{value}</span>
                        {:else}
                            {value}
                        {/if}
                    </span>
                    {#if popovers[header.key] != undefined}
                    
                        <Popover trigger="hover">
                            <svelte:component this={popovers[header.key].component} data={resolveNestedValue(item, popovers[header.key].prop ?? "")}/>
                        </Popover>
                    {/if}
                </td>
            {/each}
        </tr>
        {/each}
    </tbody>
</table>