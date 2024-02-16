<script lang="ts">
    import { onDestroy, onMount } from 'svelte';

    export let align: string = 'right';
    export let width: string = '48';
    export let contentClasses: string = 'py-1 bg-white dark:bg-gray-700';

    let widthClass: string | undefined = '';
    let alignmentClasses: string | undefined = '';
    let open: boolean = false;

    const closeOnEscape = (e: KeyboardEvent) => {
        if (open && e.key === 'Escape') {
            open = false;
        }
    };

    onMount(() => document.addEventListener('keydown', closeOnEscape));
    onDestroy(() => document.removeEventListener('keydown', closeOnEscape));

    $: widthClass = {
            48: 'w-48',
        }[width.toString()];

    $: alignmentClasses = {
            left: 'ltr:origin-top-left rtl:origin-top-right start-0',
            right: 'ltr:origin-top-right rtl:origin-top-left end-0',
            center: 'origin-top',
        }[align];

</script>


    <div class="relative">
        <button on:click={() => open = !open}>
            <slot name="trigger" />
        </button>

            {#if open}

            <button
                class="absolute block z-50 mt-2 rounded-md shadow-lg {widthClass} {alignmentClasses}"
                on:click={() => open = false}
            >
                <div class="rounded-md ring-1 ring-black ring-opacity-5 {contentClasses}" >
                    <slot name="content" />
                </div>
            </button>
            {/if}
    </div>
