<!-- @migration-task Error while migrating Svelte code: This migration would change the name of a slot making the component unusable -->
<!-- @migration-task Error while migrating Svelte code: This migration would change the name of a slot making the component unusable -->
<script lang="ts">
    import { getContext, onMount } from "svelte";
    import { writable } from "svelte/store";
    import { fade, fly, slide } from "svelte/transition";

    export let title: string = "Tab";
    export let open: boolean = false;


    const context = getContext("context") ?? {} as any;
    let active = context.active ?? writable<HTMLElement>();

    function init(node: HTMLElement) {
        active.set(node);

        const destroy = active.subscribe((elem: HTMLElement) => {
            if (elem !== node) {
                open = false;
            }
        });

        return { destroy };
    }

</script>


<button id="tab__{title.toLowerCase()}" type="button" role="tab" class="tab capitalize" on:click={() => open = true} class:tab-active={open}>
    <slot name="title">
        {title}
    </slot>
    {#if open}
        <div class="hidden">
            <div use:init transition:slide={{duration: 300}} class="w-full flex flex-col">

                <slot name="head"/>

                <slot />
            </div>
        </div>
    {/if}
</button>