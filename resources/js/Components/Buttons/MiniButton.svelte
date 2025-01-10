<script lang="ts">
    import { createBubbler } from 'svelte/legacy';

    const bubble = createBubbler();
    import { containerColors as colors } from "$lib/config";
    import { twMerge } from "tailwind-merge";

    interface Props {
        color?: "blue" | "red" | "green" | "yellow" | "gray" | "indigo" | "purple" | "pink" | "orange" | "teal" | "cyan" | "white" | "black" | "ghost"
        | "primary" | "secondary" | "accent" | "neutral" | "info" | "success" | "warning" | "error" | "base-100" | "base-200";
        type?: "button" | "submit" | "reset";
        href?: string;
        children?: import('svelte').Snippet;
        [key: string]: any
    }

    let {
        color = "primary",
        type = "button",
        href = "",
        children,
        ...rest_1
    }: Props = $props();

    const css = twMerge("border rounded px-2 max-h-6 bg-slate-300 text-black capitalize",colors[color], rest_1.class);
    const {class: _, ...rest} = rest_1;

</script>

{#if !href}
<button {type} class={css} onclick={bubble('click')} {...rest}>
    {@render children?.()}
</button>
{:else}
<a role="button" {href} class={css} onclick={bubble('click')} {...rest}>
        {@render children?.()}
</a>
{/if}