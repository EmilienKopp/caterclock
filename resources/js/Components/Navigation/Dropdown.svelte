<script lang="ts">
    import { run } from 'svelte/legacy';

    import { onDestroy, onMount } from 'svelte';

    interface Props {
        align?: string;
        width?: string;
        contentClasses?: string;
        trigger?: import('svelte').Snippet;
        content?: import('svelte').Snippet;
    }

    let {
        align = 'right',
        width = '48',
        contentClasses = 'py-1 bg-white dark:bg-gray-700',
        trigger,
        content
    }: Props = $props();

    let widthClass: string | undefined = $state('');
    let alignmentClasses: string | undefined = $state('');
    let open: boolean = $state(false);

    const closeOnEscape = (e: KeyboardEvent) => {
        if (open && e.key === 'Escape') {
            open = false;
        }
    };

    onMount(() => document.addEventListener('keydown', closeOnEscape));
    onDestroy(() => document.removeEventListener('keydown', closeOnEscape));

    run(() => {
        widthClass = {
                48: 'w-48',
            }[width.toString()];
    });

    run(() => {
        alignmentClasses = {
                left: 'ltr:origin-top-left rtl:origin-top-right start-0',
                right: 'ltr:origin-top-right rtl:origin-top-left end-0',
                center: 'origin-top',
            }[align];
    });

</script>


    <div class="relative">
        <button onclick={() => open = !open}>
            {@render trigger?.()}
        </button>

            {#if open}

            <button
                class="absolute block z-50 mt-2 rounded-md shadow-lg {widthClass} {alignmentClasses}"
                onclick={() => open = false}
            >
                <div class="rounded-md ring-1 ring-black ring-opacity-5 {contentClasses}" >
                    {@render content?.()}
                </div>
            </button>
            {/if}
    </div>
