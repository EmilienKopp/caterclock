<script lang="ts">
  export let title: string | undefined = undefined;
  
  let modal: HTMLDialogElement;
</script>



<dialog class="modal overflow-clip" bind:this={modal} {...$$restProps}>

    <div class="modal-box">
        {#if title}
            <div class="modal-title">
                <h3 class="font-bold text-lg">{title}</h3>
            </div>
        {/if}
        <p class="py-4">Press ESC key or click the button below to close</p>
        <div class="modal-action">
          <form method="dialog" class="flex flex-col w-full gap-3 items-between">
            <slot />
            <button class="btn">Close</button>
          </form>
        </div>
      </div>
</dialog>

