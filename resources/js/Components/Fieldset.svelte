<script lang="ts">
    import { twMerge } from 'tailwind-merge';
</script>

<fieldset {...$$restProps} class={twMerge("border rounded p-5",$$restProps.class)}>
    <slot />
</fieldset>