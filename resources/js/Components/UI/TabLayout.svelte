<script context="module" lang="ts">
    import { writable, type Writable } from "svelte/store";

    export interface TabInfo {
        active: Writable<HTMLElement>;
    }

    
</script>

<script lang="ts">
    import { setContext } from "svelte";
    import { twMerge } from "tailwind-merge";

    export let contentClass: string = "";
    export let open: string | undefined = undefined;

    const tablistClasses = "tabs tabs-bordered w-full";

    const context = {
        active: writable<HTMLElement>()
    }

    setContext("context", {
        ...context
    });

    function init(node: HTMLElement) {
        const destroy = context.active.subscribe((elem: HTMLElement) => {
            if(elem) node.replaceChildren(elem);
        });

        return {
            destroy
        }
    }
</script>

<div role="tablist" class={twMerge(tablistClasses,$$restProps.class)}>
    <slot />
</div>
<div role="tabpanel" aria-labelledby="id-tab" class={twMerge("pt-3",$$restProps.class,contentClass)} use:init></div>