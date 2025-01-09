<script lang="ts">
    import { categoryColors } from "$lib/config";
    import { Duration } from "$lib/duration";
    import type { Activity } from "$types";
    import { twMerge } from "tailwind-merge";

    export let activity: Activity;

    //TODO: set as settings data in DB
</script>

<div class="flex flex-row gap-2">
    <div class={twMerge("w-2 h-full",categoryColors[activity.task_category.name].bg)}>&nbsp;</div>
    {activity.task_category.name} - {Duration.toHrMinString(activity.duration)}
</div>

