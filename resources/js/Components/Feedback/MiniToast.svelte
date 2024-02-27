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
    const alertClasses: any = {
        "success": "alert-success",
        "error": "alert-error",
        "info": "alert-info",
    }

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
    $: console.log("TOAST",$toast.type);
</script>

{#if show}
<div class="toast toast-top toast-end z-[999] mt-16">
    <div role="alert" class="alert {alertClasses[$toast.type]}">
        {#if $toast.type == "success"}
            <CheckCircleFill　class="bg-inherit" />
        {:else if $toast.type == "error"}
            <Fire class="bg-inherit"/>
        {:else if $toast.type == "info"}
            <InfoSquareFill class="bg-inherit"　/>
        {/if}
        {@html $toast.message}
        <br/>
        {#if href}
            <Link {href}>{linkText ?? href}</Link>
        {/if}
    </div>
    <slot name="link"/>
</div>
{/if}
