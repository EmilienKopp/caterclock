<script lang="ts">
    import { Chart } from 'chart.js/auto';
    import { Duration } from "$lib/Duration";
    import type { Activity } from "$types";
    import { categoryColors } from '$lib/config';

    export let activities: Activity[] = [];
    export let title: string;
    export let width: number | string = 400;
    export let height: number | string = 400;

    let canvas: HTMLCanvasElement;

    $: console.log(activities)

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

    $: if(canvas) {
        const chart = new Chart(canvas, {
            type: 'doughnut',
            data: data,
        });
    }
</script>

<div class="modal-action flex justify-center h-[400px]">
    <canvas bind:this={canvas} {width} {height}></canvas>
</div>

