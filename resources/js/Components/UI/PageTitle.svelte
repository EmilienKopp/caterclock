<script lang="ts">
    export let headTitle: string;
</script>
<svelte:head>
    <title>
        {headTitle || 'FleaClock'}
    </title>
</svelte:head>
<h1 class="text-3xl flex w-full items-center justify-between">
   <slot /> 
</h1>