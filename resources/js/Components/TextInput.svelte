<script lang="ts">
    import { twMerge } from 'tailwind-merge';

    let elem: HTMLInputElement | HTMLTextAreaElement = document.createElement("input");

    export let value: string | undefined;
    export const focus = elem.focus;

    const cssClass = twMerge(
        "input input-bordered w-full max-w-xs",
        $$restProps.class
    );

</script>

{#if $$restProps.type === 'textarea'}
    <textarea
        bind:this={elem}
        bind:value
        on:input on:change on:blur on:keydown on:keyup on:keypress on:focus
        {...$$restProps}
        class={cssClass}
    ></textarea>
{:else}
<label class="form-control w-full max-w-xs">
    {#if $$slots.label}
    <div class="label">
        <span class="label-text">
            <slot name="label" />
        </span>
    </div>
    {/if}
    <input
        bind:this={elem}
        bind:value
        on:input on:change on:blur on:keydown on:keyup on:keypress on:focus
        {...$$restProps}
        class={cssClass}
    />
</label>
{/if}

