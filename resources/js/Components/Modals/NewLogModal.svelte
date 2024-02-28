<script lang="ts">
    import InputLabel from "$components/Inputs/InputLabel.svelte";
    import Select from "$components/Inputs/Select.svelte";
    import Dialog from "./Dialog.svelte";
    import { page, useForm } from "@inertiajs/svelte";
    import route from '$vendor/tightenco/ziggy';
    import { toast, queryParams } from "$lib/stores";
    import dayjs from "dayjs";
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import SecondaryButton from "$components/Buttons/SecondaryButton.svelte";

    export let open: boolean = false;

    const form = useForm({
        user_id: $page.props.auth.user.id,
        project_id: null,
        in_time: dayjs($page.props.date).format('YYYY-MM-DD') + 'T10:00:00',
        out_time: dayjs($page.props.date).format('YYYY-MM-DD') + 'T18:00:00',
        date: dayjs($page.props.query.date).format('YYYY-MM-DD')
    });

    function handleSubmit() {
        console.log($form);
        $form.post(route('timelog.store'), {
            onSuccess: () => {
                open = false;
                toast.success('Log saved successfully.');
            },
            onError: () => {
                toast.error('Error saving log.');
                console.log($form.errors);
            }
        });
    }
</script>

<Dialog title="Create a new log" on:submit={handleSubmit} bind:open >
    <div class="flex flex-col space-y-4">
        <div class="flex flex-col space-y-2">
            <InputLabel value="In Time" for="in_time">
                <input type="datetime-local" id="in_time" name="in_time" bind:value={$form.in_time} />
            </InputLabel>
            {#if $form.errors.in_time}
                <p class="text-red-500 text-sm">{$form.errors.in_time}</p>
            {/if}
        </div>
        <div class="flex flex-col space-y-2">
            <InputLabel value="Out Time" for="out_time">
                <input type="datetime-local" id="out_time" name="out_time" bind:value={$form.out_time} />
            </InputLabel>
            {#if $form.errors.out_time}
                <p class="text-red-500 text-sm">
                    {$form.errors.out_time}
                    <!-- {#if dayjs($form.in_time).get('date') == dayjs($form.out_time).get('date') && dayjs($form.in_time).isBefore(dayjs($form.out_time))}
                        <br>
                        <span class="text-xs">Did you mean to set the end time to {dayjs($form.out_time).add(1,'day')} ?</span>
                    {/if} -->
                </p>
            {/if}
        </div>
        <div class="flex flex-col space-y-2">
            <InputLabel value="Project" for="project_id">
                <Select required id="project_id" name="project_id" bind:value={$form.project_id} >
                    {#each $page.props?.projects ?? [] as project}
                        <option value={project.id}>{project.name}</option>
                    {/each}
                </Select>
            </InputLabel>
            {#if $form.errors.project_id}
                <p class="text-red-500 text-sm">{$form.errors.project_id}</p>
            {/if}
        </div>
        <div class="flex flex-row space-x-4">
            <PrimaryButton type="submit" loading={$form.processing}>Save</PrimaryButton>
            <SecondaryButton type="button" on:click={() => open = false}>Cancel</SecondaryButton>
        </div>
    </div>
</Dialog>