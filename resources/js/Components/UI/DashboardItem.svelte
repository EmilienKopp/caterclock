<script lang="ts">

    let div: HTMLDivElement;
    const defaultClasses = `border overflow-hidden shadow-md 
    sm:rounded-lg p-3 flex items-center justify-center hover:cursor-grab transition-all duration-300 ease-in-out`;

</script>

<div bind:this={div} class={defaultClasses}
    role="contentinfo"
>
    
    <slot/>
</div>