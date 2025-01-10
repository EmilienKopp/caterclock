<script lang="ts">
  import TimeZoneInfo from '$components/Widgets/TimeZoneInfo.svelte';
  import { Timezone } from '$lib/types/timezones';
  import dayjs from 'dayjs';
  import timezone from 'dayjs/plugin/timezone';
  import utc from 'dayjs/plugin/utc';

  dayjs.extend(timezone);
  dayjs.extend(utc);

  interface Props {
    tz?: Timezone;
    value?: string | number | Date;
  }

  let { tz = 'UTC', value = $bindable(dayjs().format(
    'YYYY-MM-DDTHH:mm:ss'
  )) }: Props = $props();
</script>

<div class="flex items-center">
  <input bind:value class="rounded bg-transparent" type="datetime-local" />
  <TimeZoneInfo {tz} class="flex mx-1" titled />
</div>
