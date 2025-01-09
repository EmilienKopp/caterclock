<script lang="ts">
    import { containerColors } from '$lib/config';
    import { time } from '$lib/stores';

    export let color = 'neutral';

</script>

<div class="flex items-center justify-around p-5 text-3xl {containerColors}">
    {$time}
</div>
