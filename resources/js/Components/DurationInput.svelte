<script lang="ts">
    import { Duration } from "$lib/Duration";
    import { createEventDispatcher } from "svelte";

    export let value: number = 0;
    export let max: number = Number.MAX_SAFE_INTEGER;
    export let parentTotal: number = 0;
    export let safetyOn: boolean = true;

    let hours: number = Duration.getHours(value);
    let minutes: number = Duration.getMinutes(value);

    const dispatch = createEventDispatcher();

    $: value = hours * 3600 + minutes * 60;

    let hoursInput: HTMLInputElement;
    let minutesInput: HTMLInputElement;

    function hoursChangeHandler(e: Event | KeyboardEvent) {
        const target = e.target as HTMLInputElement;
        if(target.value.toString().length > 2) {
            hours = target.value.toString().slice(0,2) == '00' ? 0 : parseInt(target.value.toString().slice(0,2));
            minutesInput.focus();
            minutesInput.value = (e as InputEvent).data ?? '0';
            minutesInput.dispatchEvent(new Event('input'));
        } else if (hours > 23) {
            hours = 23;
            minutesInput.focus();
            minutesInput.dispatchEvent(new Event('input'));
        } else if (hours < 0) {
            hours = 0;
        }
    }

    function minutesChangeHandler(e: Event | KeyboardEvent) {
        const target = e.target as HTMLInputElement;
        minutes = parseInt((target as HTMLInputElement).value);
        if(target.value.toString().length > 2 || minutes > 59) {
            minutes = 0;
            hours += (hours < 23) ? 1 : 0;
        }
    }


</script>

<div class="flex gap-1 items-center">
    <label class="flex items-center gap-1">
        <input bind:this={hoursInput} type="number" class="input input-bordered" 
            min="0" max="23" step="1" name="hours"
            class:border-red-500={safetyOn && parentTotal > max}
            bind:value={hours}
            on:focus={e => hoursInput.select()}
            on:keyup={(e) => dispatch('hourkeyup', {key: e.key})}
            on:input={hoursChangeHandler} /> hr
    </label>

    <label class="flex items-center gap-1">
        <input bind:this={minutesInput} type="number" class="input input-bordered" 
            min="0" max="60" step="1" name="minutes"
            class:border-red-500={safetyOn && parentTotal > max}
            bind:value={minutes}
            on:focus={e => minutesInput.select()}
            on:keyup={(e) => dispatch('minutekeyup', {key: e.key})}
            on:input={minutesChangeHandler} /> min
    </label>


</div>