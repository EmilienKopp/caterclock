<script lang="ts">
    import type { ButtonShape, ButtonSize, ButtonVariant } from "$types";
    import BaseButton from "./BaseButton.svelte";
    
    interface Props {
        href?: string | undefined;
        loading?: boolean;
        size?: ButtonSize;
        variant?: ButtonVariant;
        shape?: ButtonShape;
        children?: import('svelte').Snippet;
        onclick?: (e: Event) => void;
        [key: string]: any
    }

    let {
        href = undefined,
        loading = false,
        size = "md",
        variant = "danger",
        shape = undefined,
        children,
        onclick,
        ...rest
    }: Props = $props();
</script>

<BaseButton {size} {shape} {variant} {loading} {href}
    {onclick}
    {...rest}
>
    {@render children?.()}
</BaseButton>