<script lang="ts">
  import Clock from '$components/App/TimeLog/Clock.svelte';
  import ClockingForm from '$components/App/TimeLog/ClockingForm.svelte';
  import ElapsedMarker from '$components/App/TimeLog/ElapsedMarker.svelte';
  import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
  import NewLogModal from '$components/Modals/NewLogModal.svelte';
  import DashboardItem from '$components/UI/DashboardItem.svelte';
  import { preventDefault } from 'svelte/legacy';
  import { writable } from 'svelte/store';
  import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.svelte';

  interface Props {
    auth?: any;
  }

  let { auth = null }: Props = $props();

  const dropzones = writable(Array(4).fill(null));

  let newLogModalOpen = $state(false);
</script>

<AuthenticatedLayout>
  <h2
    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
  >
    Dashboard - {auth.user.name}
  </h2>

  <div
    class="py-12"
    ondragover={preventDefault(bubble('dragover'))}
    role="application"
  >
    <div
      class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-5 sm:grid-cols-2 grid-cols-1"
    >
      <DashboardItem>
        <Clock />
      </DashboardItem>
      <DashboardItem>
        <ClockingForm>
          {#snippet indicator()}
            <ElapsedMarker />
          {/snippet}
        </ClockingForm>
      </DashboardItem>
      <DashboardItem>
        <PrimaryButton on:click={() => (newLogModalOpen = true)}
          >Manually add a log entry</PrimaryButton
        >
      </DashboardItem>
    </div>
  </div>

  <NewLogModal bind:open={newLogModalOpen} />
</AuthenticatedLayout>
