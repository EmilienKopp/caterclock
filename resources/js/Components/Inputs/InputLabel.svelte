<script lang="ts">
    import { twMerge } from 'tailwind-merge';
    export let value: string;
</script>


<!-- svelte-ignore a11y-label-has-associated-control -->
<label {...$$restProps} class={twMerge("block font-medium text-sm text-gray-700 dark:text-gray-300",$$restProps.class)} >

    <span>{ value }</span>

    <slot />

</label>


<style>
    label:has(input[required]) span::after {
        color: red;
        content: " *";
    }
</style>