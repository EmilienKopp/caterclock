<script lang="ts">
   interface Props {
      headTitle: string;
      children?: import('svelte').Snippet;
   }

   let { headTitle, children }: Props = $props();
</script>
<svelte:head>
    <title>
        {headTitle || 'caterclock'}
    </title>
</svelte:head>
<h1 class="text-3xl flex w-full items-center justify-between">
   {@render children?.()} 
</h1>