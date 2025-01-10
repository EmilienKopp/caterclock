<script lang="ts">
    import { containerColors } from '$lib/config';

    let time = $state(new Date().toLocaleTimeString());
    $effect(() => {
        const interval = setInterval(() => {
            time = new Date().toLocaleTimeString();
        }, 1000);

        return () => clearInterval(interval);
    });
</script>

<div class="flex items-center justify-around p-5 text-3xl {containerColors}">
    {time}
</div>
