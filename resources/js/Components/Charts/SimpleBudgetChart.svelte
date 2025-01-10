<script lang="ts">
    import { run } from 'svelte/legacy';

    import { Chart } from 'chart.js/auto';
    
    interface Props {
        budget?: number;
        spent?: number;
        title: string;
        width?: number | string;
        height?: number | string;
    }

    let {
        budget = 0,
        spent = 0,
        title,
        width = 600,
        height = 600
    }: Props = $props();

    let canvas: HTMLCanvasElement = $state();


    const data = {
        labels: ["spent", "budget(left)"],
        datasets: [{
            data:  [spent, budget - spent],
        }]
    };

    run(() => {
        if(canvas) {
            const chart = new Chart(canvas, {
                type: 'pie',
                data: data,
            });
        }
    });
</script>

<div class="modal-action flex justify-center h-96">
    <canvas bind:this={canvas} {width} {height}></canvas>
</div>

