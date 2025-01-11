<script lang="ts">
  import MiniButton from '$components/Buttons/MiniButton.svelte';
  import OutlineButton from '$components/Buttons/OutlineButton.svelte';
  import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
  import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
  import Select from '$components/Inputs/Select.svelte';
  import Dialog from '$components/Modals/Dialog.svelte';
  import TimeZoneInfo from '$components/Widgets/TimeZoneInfo.svelte';
  import { computeActivitiesTotalDuration } from '$lib/domain/activities';
  import { Activity } from '$lib/models/Activity.svelte';
  import { DailyLog } from '$lib/models/DailyLog';
  import { TaskCategory } from '$lib/models/TaskCategory';
  import { collect, Collection } from '$lib/utils/collection';
  import { Duration } from '$lib/utils/duration';
  import type { IActivity } from '$models';
  import route from '$vendor/tightenco/ziggy';
  import { page, useForm } from '@inertiajs/svelte';
  import dayjs from 'dayjs';
  import timezone from 'dayjs/plugin/timezone';
  import utc from 'dayjs/plugin/utc';
  import { fade } from 'svelte/transition';

  dayjs.extend(timezone);
  dayjs.extend(utc);

  interface Props {
    taskCategories: TaskCategory[] | Collection<TaskCategory>;
    log: DailyLog;
  }

  let { taskCategories, log = $bindable() }: Props = $props();

  let logEntry = $state(log.timeLogs?.[0]);
  let aboveMax: boolean = $state(true);
  let loading: boolean = false;
  let safetyOn: boolean = $state(true);
  let clockEntryModalOpen = $state(false);
  let entries = $state(
    log.timeLogs?.map((entry: any) => {
      return {
        ...entry,
        in_time: dayjs(entry.in_time).format('YYYY-MM-DDTHH:mm'),
        out_time: entry.out_time
          ? dayjs(entry.out_time).format('YYYY-MM-DDTHH:mm')
          : '',
      };
    })
  );

  const taskCategoriesOptions = collect(taskCategories).toSelect('id', 'name');
  const clockEntriesForm = useForm({
    entries,
  });

  const form = $state(
    useForm({
      date: log.date,
      project_id: log.project_id,
      activities:
        log.activities
          ?.filter((a: any) => a.project_id == log.project_id)
          .map((a) => new Activity(a)) ?? [],
    })
  );

  let activitiesTotal = $derived(
    computeActivitiesTotalDuration($form.activities)
  );

  let duration = $derived(Duration.toHHMM(log?.total_seconds ?? 0));

  function openTaskModal(activity: any, index: number) {
    console.log(activity, index);
  }

  function handleKeydown(e: Event, activity: IActivity, index: number) {
    const key = (e as KeyboardEvent).key;
    console.log('DailyLogInputForm.svelte: handleKeydown: key: ', key, e);
    if (key == 'Enter') {
      addRow(activity.project_id);
    }
  }

  function handleInput(
    e: Event,
    activity: IActivity,
    unit: 'hours' | 'minutes'
  ) {
    activity.duration =
      ((activity.hours || 0) * 60 + (activity.minutes || 0)) * 60;
    if (safetyOn) {
      aboveMax = activitiesTotal > (log?.total_seconds ?? 0);
      if (aboveMax) {
        toaster.error(
          'Total duration cannot be greater than ' +
            Duration.toHHMM(log.total_seconds ?? 0)
        );
      }
    }
  }

  let totalDuration = $derived.by(() => {
    return $form.activities.reduce((acc, a) => acc + a.duration, 0);
  });

  function addRow(projectId: number | undefined) {
    if (!projectId) return;
    const newActivity = new Activity({
      project_id: projectId,
      user_id: log.user_id || $page.props.auth.user.id,
      task_category_id: 0,
      date: log.date,
      duration: 0,
    });
    $form.activities = [
      ...$form.activities,
      newActivity,
    ];
    log.activities = $form.activities;
  }

  async function save(e: Event) {
    e.preventDefault();
    loading = true;
    $form.post(route('activities.store'), {
      onSuccess: () => {
        toaster.success('Activities saved successfully');
        $form.isDirty = false;
        loading = false;
      },
      onError: (error: any) => {
        toaster.error('Error saving activities');
        console.log(error);
      },
    });
  }

  async function handleDelete() {
    if (confirm('Are you sure you want to delete this log?')) {
      $form.delete(route('timelog.destroy', { timelog: logEntry?.id }), {
        onStart: () => {
          toaster.info('Deleting log...');
        },
        onSuccess: () => {
          toaster.success('Log deleted successfully');
        },
        onError: () => {
          toaster.error('Error deleting log');
        },
      });
    }
  }

  function removeItem(index: number) {
    $form.activities = $form.activities.filter((a, i) => i != index);
  }

  function fill(index: number) {
    const remaining = (log.total_seconds ?? 0) - activitiesTotal;
    const duration = $form.activities[index].duration + remaining || 0;
    $form.activities[index].duration = Math.round(duration);
  }

  function clear(index: number) {
    $form.activities[index].duration = 0;
  }

  async function updateClockEntries() {
    console.log('Updating clock entries', $clockEntriesForm);
    $clockEntriesForm.put(route('timelog.batch-update'), {
      onSuccess: () => {
        toaster.success('Clock entries updated successfully');
        clockEntryModalOpen = false;
      },
      onError: () => {
        toaster.error('Error updating clock entries');
      },
    });
  }

  $inspect(totalDuration, $form.activities);
</script>

<form
  class="rounded border p-5"
  onsubmit={(e) => DailyLog.save(e, $form, loading)}
  transition:fade
  class:border-green-600={!$form.isDirty}
  class:border-yellow-100={$form.isDirty && !aboveMax}
  class:border-red-600={aboveMax}
>
  {#if $form.processing}
    <div class="w-full h-full opacity-70 flex items-center justify-center">
      <span class="loading loading-dots loading-lg"></span>
    </div>
  {:else}
    <div class="flex justify-between text-lg">
      <h2>
        {log.date}・{log.project_name}・({duration})
        <span
          class="tooltip"
          data-tip="Safety mode prevents you from saving activities that exceed the total duration of the log."
        >
          <button
            type="button"
            class="text-xs"
            class:text-red-500={!safetyOn}
            class:text-green-400={safetyOn}
            onclick={() => (safetyOn = !safetyOn)}
          >
            {#if safetyOn}
              <!-- ShieldFillCheck -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-shield-fill-check"
                viewBox="0 0 16 16"
              >
                <path
                  fill-rule="evenodd"
                  d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.8 11.8 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263 63 63 0 0 0-2.887-.87C9.843.266 8.69 0 8 0m2.146 5.146a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793z"
                />
              </svg>
            {:else}
              <!-- ShieldSlash -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-shield-slash"
                viewBox="0 0 16 16"
              >
                <path
                  fill-rule="evenodd"
                  d="M1.093 3.093c-.465 4.275.885 7.46 2.513 9.589a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.3 11.3 0 0 0 1.733-1.525l-.745-.745a10.3 10.3 0 0 1-1.578 1.392c-.346.244-.652.42-.893.533q-.18.085-.293.118a1 1 0 0 1-.101.025 1 1 0 0 1-.1-.025 2 2 0 0 1-.294-.118 6 6 0 0 1-.893-.533 10.7 10.7 0 0 1-2.287-2.233C3.053 10.228 1.879 7.594 2.06 4.06zM3.98 1.98l-.852-.852A59 59 0 0 1 5.072.559C6.157.266 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.483 3.626-.332 6.491-1.551 8.616l-.77-.77c1.042-1.915 1.72-4.469 1.29-7.702a.48.48 0 0 0-.33-.39c-.65-.213-1.75-.56-2.836-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524a50 50 0 0 0-1.357.39zm9.666 12.374-13-13 .708-.708 13 13z"
                />
              </svg>
            {/if}
          </button>
        </span>
      </h2>

      <div>
        <MiniButton
          class="text-xs mx-4"
          on:click={() => (clockEntryModalOpen = true)}
          >Edit clock entries</MiniButton
        >
        <MiniButton class="text-xs" on:click={handleDelete} color="warning"
          >Delete</MiniButton
        >
      </div>
    </div>
    <button class="text-xs" type="button">
      {totalDuration}
    </button>

    <table class="table">
      <thead>
        <tr>
          <th>Task Category</th>
          <th>Duration</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {#each $form.activities as activity, index}
          <tr>
            <td>
              <Select
                name="activity_{activity.project_id}[{index}]"
                label="Task Category"
                bind:value={activity.task_category_id}
                items={taskCategoriesOptions}
                mapping={{
                  labelColumn: 'name',
                  valueColumn: 'id',
                }}
              />
            </td>
            <td>
              <!-- <DurationInput
                bind:activity={$form.activities[index]}
                max={log.total_seconds}
                parentTotal={activitiesTotal}
                {safetyOn}
                handleKeydown={(e: Event) => handleKeydown(e, activity, index)}
                handleChange={handleInput}
              /> -->
              <input
                type="number"
                class="input input-bordered"
                min="0"
                max="23"
                step="1"
                name="hours"
                bind:value={activity.hours}
                onchange={(e) =>
                  handleInput(e, $form.activities[index], 'hours')}
              />

              <input
                type="number"
                class="input input-bordered"
                min="0"
                max="59"
                step="1"
                bind:value={activity.minutes}
                name="minutes"
                onchange={(e) =>
                  handleInput(e, $form.activities[index], 'minutes')}
              />
            </td>
            <td>
              <div class="dropdown">
                <div
                  tabindex="0"
                  role="button"
                  class="btn m-1 btn-ghost btn-outline"
                >
                  <!-- ThreeDots -->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-three-dots"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"
                    />
                  </svg>
                  Options
                </div>
                <ul
                  class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52"
                >
                  {#if Duration.flooredToMinute(activitiesTotal) < Duration.flooredToMinute(log.total_seconds)}
                    <li>
                      <button type="button" onclick={() => fill(index)}
                        >Fill</button
                      >
                    </li>
                  {/if}
                  <li>
                    <button type="button" onclick={() => clear(index)}
                      >Clear</button
                    >
                  </li>
                  <li>
                    <button
                      type="button"
                      class="text-red-300"
                      onclick={() => removeItem(index)}>Remove</button
                    >
                  </li>
                  <li>
                    <button
                      type="button"
                      onclick={() => openTaskModal(activity, index)}
                    >
                      Link Task
                    </button>
                  </li>
                  <li>
                    <button type="button" onclick={$form.reset()}>Reset</button>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        {/each}
        <tr>
          <td colspan="3">
            <OutlineButton
              onclick={() => addRow(log.project_id)}
              disabled={$form.processing}
            >
              Add Activity
            </OutlineButton>
            {#if $form.isDirty}
              <PrimaryButton
                type="submit"
                title={aboveMax
                  ? 'Total duration cannot be greater than ' +
                    Duration.toHHMM(log.total_seconds ?? 0)
                  : 'Save activities'}
                loading={$form.processing}
                disabled={aboveMax ||
                  activitiesTotal == 0 ||
                  log.activities?.some((a) => !a.task_category_id)}
              >
                Save
              </PrimaryButton>
            {/if}
          </td>
        </tr>
      </tbody>
    </table>
  {/if}
</form>

<Dialog title="Clock entries" bind:open={clockEntryModalOpen}>
  {#each $clockEntriesForm.entries as entry, index}
    <div class="flex items-center gap-1">
      <input
        bind:value={entry.in_time}
        class="rounded bg-transparent w-44 text-sm"
        type="datetime-local"
      />
      ~
      <input
        bind:value={entry.out_time}
        class="rounded bg-transparent w-44 text-sm"
        type="datetime-local"
      />

      <TimeZoneInfo timezone={entry.timezone} titled />
    </div>
  {/each}

  {#snippet buttons()}
    <div>
      <PrimaryButton onclick={updateClockEntries}>Save</PrimaryButton>
    </div>
  {/snippet}
</Dialog>
