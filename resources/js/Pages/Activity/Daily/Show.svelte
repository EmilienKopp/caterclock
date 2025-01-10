<script lang="ts">
  import { run } from 'svelte/legacy';

  import MiniButton from '$components/Buttons/MiniButton.svelte';
  import OutlineButton from '$components/Buttons/OutlineButton.svelte';
  import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
  import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
  import NewLogModal from '$components/Modals/NewLogModal.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import type { Activity, DailyLog, TaskCategory } from '$models';
  import route from '$vendor/tightenco/ziggy';
  import { page, router, useForm } from '@inertiajs/svelte';
  import dayjs from 'dayjs';
  import DailyLogInputForm from './DailyLogInputForm.svelte';
  

  interface PageProps {
    activities: Activity[];
    dailyLogs: DailyLog[];
    taskCategories: TaskCategory[];
    date: string;
  }

  let { activities, dailyLogs, taskCategories, date}: PageProps = $page.props;

  let selectedDate = $state(dayjs(date).format('YYYY-MM-DD'));
  let logModalOpen = $state(false);

  let form = useForm({
    date: selectedDate,
    activities: activities,
  });

  function handleDateSelection() {
    if (!selectedDate) return;
    router.get(route('activities.show', { date: selectedDate }));
  }

  async function saveAll() {
    $form.post(route('activities.store'), {
      onSuccess: () => {
        toaster.success('Activities saved successfully.');
      },
      onError: () => {
        toaster.error('Error saving activities.');
        console.log($form);
      },
    });
  }

  async function saveAllAndReturn() {
    $form.post(route('activities.store'), {
      onSuccess: () => {
        toaster.success('Activities saved successfully.');
        router.get(route('activities.index'));
      },
      onError: () => {
        toaster.error('Error saving activities.');
      },
    });
  }

  function showLogModal() {
    logModalOpen = true;
  }

  run(() => {
    $form.activities = dailyLogs?.flatMap((log) => {
      return log.activities;
    });
  });
</script>

<AuthenticatedLayout>
  <h1 class="text-2xl font-semibold">Daily Logs</h1>
  <input
    type="date"
    class="rounded bg-transparent w-44 text-sm"
    onchange={handleDateSelection}
    bind:value={selectedDate}
  />

  <div class="grid 2xl:grid-cols-2 grid-cols-1 gap-4 my-3">
    {#if dailyLogs.length == 0}
      <div class="col-span-2">
        <p>No logs found for this date.</p>
        <MiniButton type="button" color="info" on:click={showLogModal}>
          Create a new log
        </MiniButton>
      </div>
    {:else}
      {#each dailyLogs as log, i}
        {#if log.activities}
          <DailyLogInputForm bind:log={dailyLogs[i]} {taskCategories} />
        {/if}
      {/each}
      <div class="2xl:col-span-2 col-span-1">
        <PrimaryButton on:click={saveAll} loading={$form.processing}
          >Save All</PrimaryButton
        >
        <PrimaryButton on:click={saveAllAndReturn} loading={$form.processing}
          >Save All and Go Back</PrimaryButton
        >
        <OutlineButton href={route('activities.index')}>
          {#if $form.isDirty}
            Discard Changes
          {:else}
            Go Back
          {/if}
        </OutlineButton>
      </div>
    {/if}
  </div>
</AuthenticatedLayout>
<NewLogModal bind:open={logModalOpen} />
