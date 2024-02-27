<script lang="ts">
    import { twMerge } from 'tailwind-merge';
    export let value: string = "";
</script>


<!-- svelte-ignore a11y-label-has-associated-control -->
<label {...$$restProps} class={twMerge("form-control",$$restProps.class)} >

    <span>{ value }</span>

    <slot />

</label>


<style>
    label:has(input[required]) span::after,
    label:has(select[required]) span::after,
    label:has(textarea[required]) span::after {
        color: red;
        content: " *";
    }
</style>