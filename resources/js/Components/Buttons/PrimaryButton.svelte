<script lang="ts">
    import BaseButton from "./BaseButton.svelte";
    import type { ButtonSize, ButtonShape, ButtonVariant } from "$types";
    
    export let href: string | undefined = undefined;
    export let loading: boolean = false;
    export let size: ButtonSize = "md";
    export let variant: ButtonVariant = "primary";
    export let shape: ButtonShape = "block";
</script>

<BaseButton {size} {shape} {variant} {loading} {href}
    on:click {...$$restProps}
>

    <slot />

</BaseButton>