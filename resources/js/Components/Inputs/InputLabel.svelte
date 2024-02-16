<script lang="ts">
    import { twMerge } from 'tailwind-merge';
    export let value: string | undefined = undefined;
</script>


<!-- svelte-ignore a11y-label-has-associated-control -->
<label {...$$restProps} class={twMerge("block font-medium text-sm text-gray-700 dark:text-gray-300",$$restProps.class)} >
    {#if value}
        { value }
    {:else}
    <div class="flex flex-col my-1">
        <slot />
    </div>
    {/if}
</label>

