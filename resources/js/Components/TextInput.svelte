<script lang="ts">
    import { twMerge } from 'tailwind-merge';

    let elem: HTMLInputElement | HTMLTextAreaElement = document.createElement("input");

    export let value: string | undefined;
    export const focus = elem.focus;

    const cssClass = twMerge(
        "input input-bordered w-full max-w-xs",
        $$restProps.class
    );

</script>

{#if $$restProps.type === 'textarea'}
    <textarea
        bind:this={elem}
        bind:value
        on:input on:change on:blur on:keydown on:keyup on:keypress on:focus
        {...$$restProps}
        class={cssClass}
    ></textarea>
{:else}
    <input
        bind:this={elem}
        bind:value
        on:input on:change on:blur on:keydown on:keyup on:keypress on:focus
        {...$$restProps}
        class={cssClass}
    />
{/if}

