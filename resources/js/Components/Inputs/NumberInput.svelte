<script lang="ts">
    import { twMerge } from 'tailwind-merge';

    export let value: string | undefined;


    const cssClass = twMerge(
        "input input-bordered w-full max-w-xs",
        $$restProps.class
    );

</script>

<input
    bind:value
    on:input on:change on:blur on:keydown on:keyup on:keypress on:focus
    {...$$restProps}
    type="number"
    class={cssClass}
/>



