<script lang="ts">
    import { twMerge } from "tailwind-merge";
    import { Link } from '@inertiajs/svelte';

    export let href: string | undefined = undefined;

    const classes = `inline-flex items-center px-4 py-2 m-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 
    dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 
    disabled:opacity-25 disabled:hover:bg-white disabled:hover:text-gray-700 disabled:dark:hover:bg-gray-800 disabled:dark:hover:text-gray-300 disabled:cursor-not-allowed
    transition ease-in-out duration-150`

</script>

{#if !href}
    <button
        type="button"
        class={twMerge(classes, $$restProps.class)}
        on:click
        {...$$restProps}
    >
        <slot />
    </button>
{:else}
    <Link href={href} class={classes} {...$$restProps} on:click>
        <slot />
    </Link>
{/if}

