<script lang="ts">
    import type { ButtonShape, ButtonSize, ButtonVariant } from "$types";
    import BaseButton from "./BaseButton.svelte";
    
    interface Props {
        href?: string | undefined;
        loading?: boolean;
        size?: ButtonSize;
        variant?: ButtonVariant;
        shape?: ButtonShape;
        onclick?: (event: Event) => void;
        children?: import('svelte').Snippet;
        [key: string]: any
    }

    let {
        href = undefined,
        loading = false,
        size = "md",
        variant = "outline",
        shape = undefined,
        children,
        ...rest
    }: Props = $props();
</script>

<BaseButton {size} {shape} {variant} {loading} {href}
    {...rest}
>
    {@render children?.()}
</BaseButton>