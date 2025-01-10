<script lang="ts">
    import type { ButtonShape, ButtonSize, ButtonVariant } from "$types/index";
    import { Link } from '@inertiajs/svelte';
    import { twMerge } from "tailwind-merge";

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
        variant = "primary",
        shape = undefined,
        children,
        onclick,
        ...rest
    }: Props = $props();

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
        {onclick}
        {...rest}
        class={twMerge(classes,"min-h-fit h-8", rest.class)}
    >
        {#if !loading}
            {@render children?.()}
        {:else}
            <span class="loading loading-dots loading-sm"></span>
        {/if}
    </button>
{:else}
    <Link href={href} {...rest} class={twMerge(classes,"min-h-fit h-8",rest.class)}  on:click >
        {#if !loading}
            {@render children?.()}
        {:else}
            <span class="loading loading-dots loading-lg"></span>
        {/if}
    </Link>
{/if}

