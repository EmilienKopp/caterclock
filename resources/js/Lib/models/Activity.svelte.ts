import type { IActivity } from '$models';
import { ActivityBase } from './_base/ActivityBase.svelte';

export class Activity extends ActivityBase implements IActivity {

  hours = $state<number>(0);
  minutes = $state<number>(0);

  static computeDuration = (activity: IActivity): number => {
    const { hours, minutes } = activity;
    return 3600*(hours || 0) + 60*(minutes || 0);
  }

  get $duration(): number {
    return Activity.computeDuration(this);
  }
}