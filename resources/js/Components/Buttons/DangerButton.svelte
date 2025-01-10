<script lang="ts">
    import BaseButton from "./BaseButton.svelte";
    import type { ButtonSize, ButtonShape, ButtonVariant } from "$types";
    
    interface Props {
        href?: string | undefined;
        loading?: boolean;
        size?: ButtonSize;
        variant?: ButtonVariant;
        shape?: ButtonShape;
        children?: import('svelte').Snippet;
        [key: string]: any
    }

    let {
        href = undefined,
        loading = false,
        size = "md",
        variant = "danger",
        shape = undefined,
        children,
        ...rest
    }: Props = $props();
</script>

<BaseButton {size} {shape} {variant} {loading} {href}
    on:click
    {...rest}
>
    {@render children?.()}
</BaseButton>