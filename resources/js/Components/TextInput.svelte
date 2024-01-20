<script lang="ts">
    import { twMerge } from 'tailwind-merge';

    let elem: HTMLInputElement | HTMLTextAreaElement = document.createElement("input");

    export let value: string | undefined;
    export const focus = elem.focus;

    const cssClass = twMerge(
        "border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm",
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

