<script lang="ts">
    import { createBubbler } from 'svelte/legacy';

    const bubble = createBubbler();
    import { twMerge } from 'tailwind-merge';

    interface Props {
        value: string | undefined;
        [key: string]: any
    }

    let { value = $bindable(), ...rest }: Props = $props();


    const cssClass = twMerge(
        "input input-bordered w-full max-w-xs",
        rest.class
    );

</script>

<input
    bind:value
    oninput={bubble('input')} onchange={bubble('change')} onblur={bubble('blur')} onkeydown={bubble('keydown')} onkeyup={bubble('keyup')} onkeypress={bubble('keypress')} onfocus={bubble('focus')}
    {...rest}
    type="number"
    class={cssClass}
/>



