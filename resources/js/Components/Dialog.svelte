<script lang="ts">
    import { onMount } from 'svelte';
    import MiniButton from './MiniButton.svelte';

    export let title: string = '';
    export let open: boolean = false;

    let dialog: HTMLDialogElement;

    onMount(() => {
        dialog.addEventListener('click', (e: Event) => {
            // Detect click outside of dialog (in the modal overlay/backdrop)
            const target = e.target as HTMLElement;
            if(target == dialog && !target.classList.contains('modal-box')) {
                close(e);
            }
        });
    });

    function close(e: Event) {
        if(e.type == 'click' || (e.type == 'keypress' && (e as KeyboardEvent).key == 'Escape')) {
            dialog.close();
            open = false;
        }
    }

    $: if(open) {
        dialog?.showModal();
    } else {
        dialog?.close();
    }

</script>

<dialog id="my_modal_1" class="modal" bind:this={dialog}>
    <div class="modal-box">
        <h3 class="font-bold text-lg flex items-center justify-between">
            {title}
        </h3>
        <div class="py-2 text-xs flex items-center justify-between">
            Press ESC key or click the button below to close
            <MiniButton color="gray" class="text-xs" on:click={close}>close</MiniButton>
        </div>
        <slot />
    </div>
</dialog>

