<script lang="ts">
    import { Duration } from "$lib/Duration";
    import { createEventDispatcher } from "svelte";

    export let value: number = 0;
    export let max: number = Number.MAX_SAFE_INTEGER;

    let hours: number = value ? Math.floor(value / 3600) : 0;
    let minutes: number = value ? Math.floor((value % 3600) / 60) : 0;

    const dispatch = createEventDispatcher();

    $: if(value >= 0) {
        hours = Math.floor(value / 3600);
        minutes = Math.floor((value % 3600) / 60);
    }
    let hoursInput: HTMLInputElement;
    let minutesInput: HTMLInputElement;

    function hoursChangeHandler(e: Event | KeyboardEvent) {
        const target = e.target as HTMLInputElement;
        
        if(target.value.toString().length > 2) {
            hours = target.value.toString().slice(0,2) == '00' ? 0 : parseInt(target.value.toString().slice(0,2));
            minutesInput.focus();
            minutesInput.value = (e as InputEvent).data ?? '0';
            minutesInput.dispatchEvent(new Event('input'));
        } else if (target.value > 23) {
            hours = 23;
            minutesInput.focus();
            minutesInput.dispatchEvent(new Event('input'));
        } else if (hours < 0) {
            hours = 0;
        }
        hours = parseInt((e.target as HTMLInputElement).value);
        value = Duration.flooredToMinute(hours * 3600 + (minutes * 60));
        dispatch('durationInput', {value, target, key: (e as KeyboardEvent).key, timestamp: Date.now()});
    }

    function minutesChangeHandler(e: Event | KeyboardEvent) {
        const target = e.target as HTMLInputElement;
        minutes = parseInt((target as HTMLInputElement).value);
        if(target.value.toString().length > 2 || minutes > 59) {
            minutes = 0;
            hours += (hours < 23) ? 1 : 0;
        }
        value = Duration.flooredToMinute(hours * 3600 + (minutes * 60));
        dispatch('durationInput', {value, target, key: (e as KeyboardEvent).key, timestamp: Date.now()});
    }

    
</script>

<div class="flex gap-1 items-center">
    <input bind:this={hoursInput} type="number" class="w-16 bg-transparent rounded" 
        min="0" max="23" step="1" name="hours"
        on:focus={e => hoursInput.select()}
        on:keyup={(e) => dispatch('hourkeyup', {key: e.key})}
        on:input={hoursChangeHandler} value={hours}/> hours

    <input bind:this={minutesInput} type="number" class="w-16 bg-transparent rounded"
        min="0" max="60" step="1" name="minutes"
        on:focus={e => minutesInput.select()}
        on:keyup={(e) => dispatch('minutekeyup', {key: e.key})}
        on:input={minutesChangeHandler}  value={minutes} /> minutes

</div>
