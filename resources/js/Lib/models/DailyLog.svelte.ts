import type { IDailyLog } from '$models';
import { DailyLogBase } from './_base/DailyLogBase.svelte';
;

export class DailyLog extends DailyLogBase implements IDailyLog {

  static make(data: IDailyLog): DailyLog {
    return new DailyLog(data);
  }

}