<script lang="ts" module>
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
    };

    export type DropdownAction = {
		label: string;
		action?: (item: any) => void;
		href?: string;
		disabled?: boolean;
		classes?: string;
	};
</script>
<script lang="ts">
    import MiniButton from "$components/Buttons/MiniButton.svelte";

    import { dotResolve } from "$lib/utils/objects";

    import route from "$vendor/tightenco/ziggy";
    import { Popover } from "flowbite-svelte";
    import { twMerge } from "tailwind-merge";

    interface Props {
        data?: any[];
        title?: string;
        headers?: Header[];
        popovers?: PopoverList;
        classes?: { [key: string]: string };
        actions?: DropdownAction[];
    }

    let {
        data = [],
        title = "Companies",
        headers = [
        { label: "Company", key: "name", route: "companies.show" },
    ],
        popovers = {},
        classes = {},
        actions = []
    }: Props = $props();
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
        <tr>
            {#each headers as header}
                {@const unformatted = dotResolve(item, header.key) ?? ""}
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
                            {@const SvelteComponent = popovers[header.key].component}
                            <SvelteComponent data={dotResolve(item, popovers[header.key].prop ?? "")}/>
                        </Popover>
                    {/if}
                </td>
            {/each}
            {#if actions.length}
                <td>
                    {#each actions as action}
                        <MiniButton href={action.href} on:click={() => action.action?.(item)}>{action.label}</MiniButton>
                    {/each}
                </td>
            {/if}
        </tr>
        {/each}
    </tbody>
</table>