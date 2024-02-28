<script lang="ts">
    import { time, datetime} from '$lib/stores';
    import { containerColors } from '$lib/config';

    export let color = 'neutral';

</script>

<div class="flex items-center justify-around p-5 text-3xl {containerColors}">
    {$time}
</div>
