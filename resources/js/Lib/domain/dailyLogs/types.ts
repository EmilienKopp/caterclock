import type { IDailyLog } from "$models";


export type DailyLogGroupedByDate = {
  [key: string]: IDailyLog[];
}