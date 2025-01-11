<script lang="ts">
  import type { Activity } from '$lib/models/Activity.svelte';

  interface Props {
    activity: Activity;
    max?: number;
    parentTotal?: number;
    safetyOn?: boolean;
    hours?: number;
    minutes?: number;
    handleKeydown?: (e: Event) => void;
    handleChange?: (e: Event) => void;
  }

  let {
    activity = $bindable(),
    max = Number.MAX_SAFE_INTEGER,
    parentTotal = 0,
    safetyOn = true,
    hours = 0,
    minutes = 0,
    handleKeydown,
    handleChange,
  }: Props = $props();

  let hoursInput: HTMLInputElement | undefined = $state();
  let minutesInput: HTMLInputElement | undefined = $state();

  function hoursChangeHandler() {
    if (hours > 23) {
      hours = 23;
      if (minutesInput) {
        minutesInput.focus();
        minutesInput.dispatchEvent(new Event('input'));
      }
    } else if (hours < 0) {
      hours = 0;
    }
    activity.duration = hours * 3600 + minutes * 60;
  }

  function minutesChangeHandler() {
    if (minutes > 59) {
      hours += Math.floor(minutes / 60);
      minutes = minutes % 60;
    }
    activity.duration = hours * 3600 + minutes * 60;
    console.log(activity.duration, hours, minutes);
  }

  $inspect(activity.duration, hours, minutes);
</script>

<div class="flex gap-1 items-center">
  <label class="flex items-center gap-1">
    <input
      bind:this={hoursInput}
      type="number"
      class="input input-bordered"
      min="0"
      max="23"
      step="1"
      name="hours"
      class:border-red-500={safetyOn && parentTotal > max}
      bind:value={hours}
      onfocus={(e) => hoursInput?.select()}
      onkeydown={handleKeydown}
      oninput={handleChange}
    /> hr
  </label>

  <label class="flex items-center gap-1">
    <input
      bind:this={minutesInput}
      type="number"
      class="input input-bordered"
      min="0"
      max="60"
      step="1"
      name="minutes"
      class:border-red-500={safetyOn && parentTotal > max}
      bind:value={minutes}
      onfocus={(e) => minutesInput?.select()}
      onkeydown={handleKeydown}
      oninput={handleChange}
    /> min
  </label>
</div>
