<script lang="ts">
  import DailyLogInputForm from './DailyLogInputForm.svelte';

    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import Select from "$components/Select.svelte";
    import { Duration } from "$lib/Duration";
    import TextInput from "$components/TextInput.svelte";
    import { Input } from "flowbite-svelte";
    import DurationInput from "$components/DurationInput.svelte";
    import { toast } from "$lib/stores";

    export let activities: any[];
    export let dailyLogs: any[];
    export let taskCategories: any[];

</script>

<AuthenticatedLayout>
    <div class="grid grid-cols-2 gap-4">
        {#each dailyLogs as log}
            <DailyLogInputForm {log} {taskCategories} activities={
                activities.filter(a => a.project_id == log.project_id)
            } />
        {/each}
    </div>
</AuthenticatedLayout>
