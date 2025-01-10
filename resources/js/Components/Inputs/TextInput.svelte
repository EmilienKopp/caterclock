<script lang="ts">
    import { createBubbler } from 'svelte/legacy';

    const bubble = createBubbler();
    import { twMerge } from 'tailwind-merge';

    let elem: HTMLInputElement | HTMLTextAreaElement = $state(document.createElement("input"));

    interface Props {
        value: string | undefined;
        label?: import('svelte').Snippet;
        [key: string]: any
    }

    let { value = $bindable(), label, ...rest }: Props = $props();
    export const focus = elem.focus;

    const cssClass = twMerge(
        "input input-bordered w-full max-w-xs",
        rest.class
    );

</script>

{#if rest.type === 'textarea'}
    <textarea
        bind:this={elem}
        bind:value
        oninput={bubble('input')} onchange={bubble('change')} onblur={bubble('blur')} onkeydown={bubble('keydown')} onkeyup={bubble('keyup')} onkeypress={bubble('keypress')} onfocus={bubble('focus')}
        {...rest}
        class={cssClass}
    ></textarea>
{:else}
<label class="form-control w-full max-w-xs">
    {#if label}
    <div class="label">
        <span class="label-text">
            {@render label?.()}
        </span>
    </div>
    {/if}
    <input
        bind:this={elem}
        bind:value
        oninput={bubble('input')} onchange={bubble('change')} onblur={bubble('blur')} onkeydown={bubble('keydown')} onkeyup={bubble('keyup')} onkeypress={bubble('keypress')} onfocus={bubble('focus')}
        {...rest}
        class={cssClass}
    />
</label>
{/if}

