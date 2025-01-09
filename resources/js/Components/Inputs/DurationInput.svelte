<script lang="ts">
    import { Duration } from "$Lib/duration";
    import { createEventDispatcher } from "svelte";

    export let activity: any;
    export let max: number = Number.MAX_SAFE_INTEGER;
    export let parentTotal: number = 0;
    export let safetyOn: boolean = true;

    $: hours = Duration.getHours(activity.duration);
    $: minutes = Duration.getMinutes(activity.duration);

    const dispatch = createEventDispatcher();

    // $: activity.duration = hours * 3600 + minutes * 60;

    let hoursInput: HTMLInputElement;
    let minutesInput: HTMLInputElement;

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
            on:focus={e => hoursInput.select()}
            on:keydown={(e) => dispatch('hourkeydown', {key: e.key})}
            on:input={hoursChangeHandler} /> hr
    </label>

    <label class="flex items-center gap-1">
        <input bind:this={minutesInput} type="number" class="input input-bordered" 
            min="0" max="60" step="1" name="minutes"
            class:border-red-500={safetyOn && parentTotal > max}
            value={minutes}
            on:focus={e => minutesInput.select()}
            on:keydown={(e) => dispatch('minutekeydown', {key: e.key})}
            on:input={minutesChangeHandler} /> min
    </label>


</div>