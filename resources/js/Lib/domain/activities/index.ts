import { Activity } from "$lib/models/Activity.svelte";

export function computeActivitiesTotalDuration(activities: Activity[]): number {
  return activities
    .reduce((a: number, b: any) => a + b.duration, 0)
}