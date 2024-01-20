<script lang="ts">
    export let message: string | undefined;
</script>

{#if message}
    <div {...$$restProps}>
        <p class="text-sm text-red-600 dark:text-red-400">
            { message }
        </p>
    </div>
{/if}
