<script lang="ts">
    interface Props {
        children?: import('svelte').Snippet;
    }

    let { children }: Props = $props();

    let div: HTMLDivElement = $state();
    const defaultClasses = `border overflow-hidden shadow-md 
    sm:rounded-lg p-3 flex items-center justify-center hover:cursor-grab transition-all duration-300 ease-in-out`;

</script>

<div bind:this={div} class={defaultClasses}
    role="contentinfo"
>
    
    {@render children?.()}
</div>