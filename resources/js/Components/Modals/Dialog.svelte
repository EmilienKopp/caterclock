<script lang="ts">
    import { onMount } from 'svelte';
    import MiniButton from '$components/Buttons/MiniButton.svelte';
    import { twMerge } from 'tailwind-merge';

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

<dialog class="modal" bind:this={dialog} >
    <form on:submit|preventDefault class={twMerge("modal-box border border-gray-400 flex flex-col w-full",$$restProps.class)} >
        <h3 class="font-bold text-lg flex items-center justify-between">
            {title}
        </h3>
        <div class="py-2 text-xs flex items-center justify-between">
            Press ESC key or click the button to the right to close
            <MiniButton color="warning" on:click={close}>close</MiniButton>
        </div>
        <div class="my-4">
            <slot />
        </div>
        <slot name="buttons"/>
    </form>
</dialog>

