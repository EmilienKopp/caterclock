<script lang="ts">
    interface Props {
        message: string | undefined;
        [key: string]: any
    }

    let { message, ...rest }: Props = $props();
</script>

{#if message}
    <div {...rest}>
        <p class="text-sm text-red-600 dark:text-red-400">
            { message }
        </p>
    </div>
{/if}
