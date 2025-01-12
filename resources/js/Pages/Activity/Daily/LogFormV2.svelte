<script lang="ts">
  import type { IDailyLog, ITaskCategory } from '$models';
  import { useForm } from '@inertiajs/svelte';

  interface Props {
    log: IDailyLog;
    taskCategories: ITaskCategory[];
  }

  let { log, taskCategories }: Props = $props();

  const form = useForm(log);
  $inspect(log, $form);
  let total = $derived($form.activities?.reduce((acc, activity) => {
      return acc + activity.hours * 3600 + activity.minutes * 60;
    }, 0)
  );
</script>

<fieldset class="flex flex-col space-y-4">
  {#if $form.activities?.length}
    {#each $form.activities as activity, index (activity.id)}
      <div class="flex items-center justify-center gap-2">
        <select bind:value={$form.activities[index].task_category_id}>
          {#each taskCategories as taskCategory}
            <option value={taskCategory.id}>{taskCategory.name}</option>
          {/each}
        </select>
        <input type="number" bind:value={$form.activities[index].hours} />
        <input type="number" bind:value={$form.activities[index].minutes} />
  
        
      </div>
      
    {/each}
  {/if}
</fieldset>
