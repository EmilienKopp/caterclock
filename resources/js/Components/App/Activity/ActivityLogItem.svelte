<script lang="ts">
    import { categoryColors } from "$lib/config";
    import { Duration } from "$lib/utils/duration";
    import type { Activity } from "$types";
    import { twMerge } from "tailwind-merge";

    interface Props {
        activity: Activity; //TODO: set as settings data in DB
    }

    let { activity }: Props = $props();

    
</script>

<div class="flex flex-row gap-2">
    <div class={twMerge("w-2 h-full",categoryColors[activity.task_category.name].bg)}>&nbsp;</div>
    {activity.task_category.name} - {Duration.toHrMinString(activity.duration)}
</div>

