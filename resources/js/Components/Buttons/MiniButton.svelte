<script lang="ts">
    import { containerColors as colors } from "$lib/config";
    import { twMerge } from "tailwind-merge";

    export let color: "blue" | "red" | "green" | "yellow" | "gray" | "indigo" | "purple" | "pink" | "orange" | "teal" | "cyan" | "white" | "black" | "ghost"
        | "primary" | "secondary" | "accent" | "neutral" | "info" | "success" | "warning" | "error" | "base-100" | "base-200" = "primary";
    export let type: "button" | "submit" | "reset" = "button";
    export let href: string = "";

    const css = twMerge("border rounded px-2 max-h-6 bg-slate-300 text-black capitalize",colors[color], $$restProps.class);
    const {class: _, ...rest} = $$restProps;

</script>

{#if !href}
<button {type} class={css} on:click {...rest}>
    <slot></slot>
</button>
{:else}
<a role="button" {href} class={css} on:click {...rest}>
        <slot></slot>
</a>
{/if}