<script lang="ts">
    import { containerColors } from '$lib/config';
    import { time } from '$lib/stores.svelte';
    import { get } from 'svelte/store';

    interface Props {
        color?: string;
    }

    let { color = 'neutral' }: Props = $props();

</script>

<div class="flex items-center justify-around p-5 text-3xl {containerColors}">
    {get(time)}
</div>
