<script lang="ts">
  interface Props {
    value?: string | number | null;
    options?: SelectOption[];
    name: string;
    mapping?: { labelColumn: string; valueColumn: string };
    children?: import('svelte').Snippet;
    onchange?: (e: Event) => void;
    onclick?: (e: Event) => void;
    oninput?: (e: Event) => void;
    onblur?: (e: Event) => void;
    onfocus?: (e: Event) => void;
    [key: string]: any;
  }

  let {
    value = $bindable(),
    options,
    name,
    mapping = { labelColumn: 'label', valueColumn: 'value' },
    children,
    ...rest
  }: Props = $props();
</script>

<select
  bind:value
  {name}
  {...rest}
  class="select select-bordered w-full max-w-xs"
>
  {#if options?.length > 0}
    {#each options ?? [] as item, index}
      <option value={item.value}>{item.label}</option>
    {/each}
  {:else}
    {@render children?.()}
  {/if}
</select>
