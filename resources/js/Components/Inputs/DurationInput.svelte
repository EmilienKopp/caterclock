<script lang="ts">
    import { run } from 'svelte/legacy';

    import { Duration } from "$lib/utils/duration";
    import { createEventDispatcher } from "svelte";

    interface Props {
        activity: any;
        max?: number;
        parentTotal?: number;
        safetyOn?: boolean;
    }

    let {
        activity = $bindable(),
        max = Number.MAX_SAFE_INTEGER,
        parentTotal = 0,
        safetyOn = true
    }: Props = $props();

    let hours;
    run(() => {
        hours = Duration.getHours(activity.duration);
    });
    let minutes;
    run(() => {
        minutes = Duration.getMinutes(activity.duration);
    });

    const dispatch = createEventDispatcher();

    // $: activity.duration = hours * 3600 + minutes * 60;

    let hoursInput: HTMLInputElement = $state();
    let minutesInput: HTMLInputElement = $state();

    function hoursChangeHandler(e: Event | KeyboardEvent) {
        const target = e.target as HTMLInputElement;
        hours = parseInt((target as HTMLInputElement).value);
        if(target.value.toString().length > 2) {
            hours = target.value.toString().slice(0,2) == '00' ? 0 : parseInt(target.value.toString().slice(0,2));
            minutesInput.focus();
            minutesInput.value = (e as InputEvent).data ?? '0';
            minutesInput.dispatchEvent(new Event('input'));
        } else if (hours > 23) {
            console.log("hours", hours, "minutes", minutes);
            hours = 23;
            minutesInput.focus();
            minutesInput.dispatchEvent(new Event('input'));
        } else if (hours < 0) {
            console.log("hours negative");
            hours = 0;
        }
        activity.duration = hours * 3600 + minutes * 60;
    }

    function minutesChangeHandler(e: Event | KeyboardEvent) {
        const target = e.target as HTMLInputElement;
        minutes = parseInt((target as HTMLInputElement).value);
        if(target.value.toString().length > 2 || minutes > 59) {
            hours += Math.floor(minutes / 60);
            minutes = minutes % 60;
        }
        activity.duration = hours * 3600 + minutes * 60;
    }


</script>

<div class="flex gap-1 items-center">
    <label class="flex items-center gap-1">
        <input bind:this={hoursInput} type="number" class="input input-bordered" 
            min="0" max="23" step="1" name="hours"
            class:border-red-500={safetyOn && parentTotal > max}
            value={hours}
            onfocus={e => hoursInput.select()}
            onkeydown={(e) => dispatch('hourkeydown', {key: e.key})}
            oninput={hoursChangeHandler} /> hr
    </label>

    <label class="flex items-center gap-1">
        <input bind:this={minutesInput} type="number" class="input input-bordered" 
            min="0" max="60" step="1" name="minutes"
            class:border-red-500={safetyOn && parentTotal > max}
            value={minutes}
            onfocus={e => minutesInput.select()}
            onkeydown={(e) => dispatch('minutekeydown', {key: e.key})}
            oninput={minutesChangeHandler} /> min
    </label>


</div>