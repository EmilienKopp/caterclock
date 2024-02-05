<script lang="ts" context="module">
    export type Header = {
        label: string;
        key: string;
        format?: (value: any) => string;
        href?: string;
        route?: string;
        asBadge?: boolean;
        popoverComponent?: any;
        popoverProp?: string;
    };
</script>
<script lang="ts">
    import { resolveNestedValue } from "$lib/Objects";

    import route from "$vendor/tightenco/ziggy";
    import { Popover } from "flowbite-svelte";
    import { SvelteComponent, SvelteComponentTyped } from "svelte";
    import { format } from "svelte-i18n";

    export let data: any[] = [];
    export let title: string = "Companies";
    export let headers: Header[] = [
        { label: "Company", key: "name", route: "companies.show" },
    ];
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
                {@const value = header.format ? header.format(unformatted) : unformatted}
                {@const uid = Math.random().toString(36).substring(7)}
                <td>
                    <span>
                        {#if header.route}
                            <a href={route(header.route, item.id)}>{value}</a>
                        {:else if header.asBadge}
                            <span class="badge">{value}</span>
                        {:else}
                            {value}
                        {/if}
                    </span>
                    {#if header.popoverComponent != undefined}
                        <Popover trigger="hover">
                            <svelte:component this={header.popoverComponent} data={resolveNestedValue(item, header?.popoverProp ?? "")}/>
                        </Popover>
                    {/if}
                </td>
            {/each}
        </tr>
        {/each}
    </tbody>
</table>