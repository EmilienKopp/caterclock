<script lang="ts">
    import { twMerge } from "tailwind-merge";
    import { Link } from '@inertiajs/svelte';
    import { ButtonShape, ButtonVariant } from "$types";

    export let href: string | undefined = undefined;
    export let loading: boolean = false;
    export let size: ButtonSize = "md";
    export let variant: ButtonVariant = "primary";
    export let shape: ButtonShape = undefined;

    const sizes: any = {
        xs: "btn-xs",
        sm: "btn-sm",
        md: "",
        lg: "btn-lg",
        xl: "btn-xl",
    };

    const variants: any = {
        primary: "btn-primary",
        secondary: "btn-secondary",
        danger: "btn-danger",
        ghost: "btn-ghost",
        outline: "btn-outline",
        link: "btn-link",
        glass: "glass",
        success: "btn-success",
        warning: "btn-warning",
        info: "btn-info",
        accent: "btn-accent",
        error: "btn-error",
    }

    const shapes: any = {
        wide: "btn-wide",
        circle: "btn-circle",
        square: "btn-square",
        clock: "btn-clock",
    }

    const classes = `btn ${variants[variant]} ${sizes[size]} ${shape ? shapes[shape] : ''}`;

</script>

{#if !href}
    <button
        type="button"
        on:click
        {...$$restProps}
        class={twMerge(classes,"min-h-fit h-8", $$restProps.class)}
    >
        <slot />
    </button>
{:else}
    <Link href={href} {...$$restProps} class={twMerge(classes,"min-h-fit h-8",$$restProps.class)}  on:click >
        <slot />
    </Link>
{/if}

