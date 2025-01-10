<script lang="ts">
    import { run } from 'svelte/legacy';

    import { categoryColors } from '$lib/config';
    import { Duration } from "$lib/utils/duration";
    import type { Activity } from "$types";
    import { Chart } from 'chart.js/auto';

    interface Props {
        activities?: Activity[];
        title: string;
        width?: number | string;
        height?: number | string;
    }

    let {
        activities = [],
        title,
        width = 400,
        height = 400
    }: Props = $props();

    let canvas: HTMLCanvasElement = $state();

    run(() => {
        console.log(activities)
    });

    const data = {
        labels: activities.map(activity => activity.task_category.name),
        datasets: [{
            data: activities.map(activity => activity.duration),
            backgroundColor: activities.map(activity => categoryColors[activity.task_category.name].rgb),
            tooltip: {
                callbacks: {
                    label: function(context: any) {
                        return Duration.toHrMinString(context.parsed);
                    }
                }
            }
        }]
    };

    run(() => {
        if(canvas) {
            const chart = new Chart(canvas, {
                type: 'doughnut',
                data: data,
            });
        }
    });
</script>

<div class="modal-action flex justify-center h-[400px]">
    <canvas bind:this={canvas} {width} {height}></canvas>
</div>

