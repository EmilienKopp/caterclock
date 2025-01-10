<script lang="ts">
    import { twMerge } from 'tailwind-merge';
    interface Props {
        value?: string;
        children?: import('svelte').Snippet;
        [key: string]: any
    }

    let { value = "", children, ...rest }: Props = $props();
</script>


<!-- svelte-ignore a11y_label_has_associated_control -->
<label {...rest} class={twMerge("form-control",rest.class)} >

    <span>{ value }</span>

    {@render children?.()}

</label>


<style>
    label:has(:global(input[required])) span::after,
    label:has(:global(select[required])) span::after,
    label:has(:global(textarea[required])) span::after {
        color: red;
        content: " *";
    }
</style>