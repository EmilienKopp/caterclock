<script lang="ts">
  interface Props {
    title?: string | undefined;
    children?: import('svelte').Snippet;
    [key: string]: any
  }

  let { title = undefined, children, ...rest }: Props = $props();
  
  let modal: HTMLDialogElement = $state();
</script>



<dialog class="modal overflow-clip" bind:this={modal} {...rest}>

    <div class="modal-box">
        {#if title}
            <div class="modal-title">
                <h3 class="font-bold text-lg">{title}</h3>
            </div>
        {/if}
        <p class="py-4">Press ESC key or click the button below to close</p>
        <div class="modal-action">
          <form method="dialog" class="flex flex-col w-full gap-3 items-between">
            {@render children?.()}
            <button class="btn">Close</button>
          </form>
        </div>
      </div>
</dialog>

