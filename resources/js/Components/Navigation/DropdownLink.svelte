<script lang="ts">
    import { Link } from '@inertiajs/svelte';

    interface Props {
        href: string;
        children?: import('svelte').Snippet;
        [key: string]: any
    }

    let { href, children, ...rest }: Props = $props();
</script>

<template>
    <Link
        {href}
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
        {...rest}
    >
        {@render children?.()}
    </Link>
</template>
