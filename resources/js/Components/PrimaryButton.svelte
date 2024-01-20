<script lang="ts">
    import { twMerge } from "tailwind-merge";
    import { Link } from '@inertiajs/svelte';

    export let href: string | undefined = undefined;

    const cssClass = `inline-flex items-center m-2 px-4 py-2 bg-gray-800 justify-center dark:bg-gray-200 
    border border-transparent rounded-md font-semibold text-xs text-white 
    dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white 
    focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 
    focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
    dark:focus:ring-offset-gray-800 transition ease-in-out duration-150
    disabled:opacity-25 disabled:cursor-not-allowed disabled:hover:bg-gray-800 disabled:hover:text-white
    disabled:dark:hover:bg-gray-200 disabled:dark:hover:text-gray-800 disabled:hover:cursor-not-allowed`;
</script>

{#if !href}
<button
    class={twMerge(cssClass,$$restProps.class)}
    on:click on:blur on:keydown on:keyup on:keypress
    {...$$restProps} >

    <slot />
    
</button>
{:else}
    <Link {href} class={twMerge(cssClass,$$restProps.class)}
        on:click on:blur on:keydown on:keyup on:keypress
        {...$$restProps} 
    >
        <slot />
    </Link>
{/if}

