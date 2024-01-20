<script lang="ts">
    import { Toast } from 'flowbite-svelte';
    import { toast } from '$lib/stores.ts';
    import { fly } from 'svelte/transition';
    import { onDestroy, onMount } from 'svelte';
    import { CheckCircleFill, Fire, InfoSquareFill, Record } from 'svelte-bootstrap-icons';
    import { Link } from '@inertiajs/svelte';
    import { twMerge } from 'tailwind-merge';

    export let href: string | undefined = undefined;
    export let linkText: string | undefined = undefined;
    export let yOffsetRem: number = 12;
    export let xOffsetRem: number = 12;
    export let show = false

    let position: "top-right" | "none" | "top-left" | "bottom-left" | "bottom-right" | undefined = $toast.options?.position ?? "top-right";

    const yOffset = position?.split('-')[0] + '-' + yOffsetRem;
    const xOffset = position?.split('-')[1] + '-' + xOffsetRem;

    onMount(() => {
        $toast.show = false;
    });

    onDestroy(() => {
        $toast.show = false;
    });

    function close() {
        $toast.message = ""
        $toast.show = false;
    }

    const textColors: any = {
        success: "text-green-500",
        error: "text-red-500",
        info: "text-blue-500",
    }

    $: if($toast.show) {
        setTimeout(() =>  {
            $toast.show = false;
        }, $toast.options?.duration);
    }

    $: show = $toast.show;
    $: console.log("TOAST",xOffset, yOffset);
</script>

<div class="fixed top-0 right-72">
    <Toast color={$toast.options?.color}
        bind:open={show} on:close={close}>

        <svelte:fragment slot="icon">
            <!-- Insert n Icon component or a <i>, whatever suits you. -->
            {#if $toast.type == "success"}
                <CheckCircleFill class={textColors[$toast.type]}/>
            {:else if $toast.type == "error"}
                <Fire class={textColors[$toast.type]}/>
            {:else if $toast.type == "info"}
                <InfoSquareFill class={textColors[$toast.type]}/>
            {/if}
        </svelte:fragment>
        <div class="flex flex-col items-start">
            {@html $toast.message}
            <br/>
            {#if href}
                <Link {href}>{linkText ?? href}</Link>
            {/if}
        </div>
        <slot name="link"/>
    </Toast>

</div>
