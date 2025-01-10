<script lang="ts">
    import { twMerge } from 'tailwind-merge';
    interface Props {
        children?: import('svelte').Snippet;
        [key: string]: any
    }

    let { children, ...rest }: Props = $props();
</script>

<fieldset {...rest} class={twMerge("border rounded p-5",rest.class)}>
    {@render children?.()}
</fieldset>