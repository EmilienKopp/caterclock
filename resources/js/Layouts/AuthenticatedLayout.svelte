<script lang="ts">
  import Toast from '$components/Feedback/Toast/Toast.svelte';
  import Navbar from '$components/Navigation/Navbar.svelte';
  import type { RouteItem } from '$types';
  import { page, router } from '@inertiajs/svelte';
  import dayjs from 'dayjs';
  import { sineInOut } from 'svelte/easing';
  import { fade } from 'svelte/transition';

  interface Props {
    header?: import('svelte').Snippet;
    children?: import('svelte').Snippet;
  }

  let { header, children }: Props = $props();
  const { auth, roles } = $page.props;

  console.log(header, children, auth, roles);

  let showingNavigationDropdown = false;

  const menu: RouteItem[] = [
    { label: 'Clock', route: 'timelog.index' },
    { label: 'Dashboard', route: 'dashboard' },
    {
      label: 'Employees',
      route: 'employees.index',
      availableTo: [roles.owner, roles.admin, roles.manager],
    },
    {
      label: 'Activity',
      route: 'activities.index',
      children: [
        { label: 'Monthly', route: 'activities.index' },
        {
          label: 'Today',
          route: 'activities.show',
          params: { date: dayjs().format('YYYY-MM-DD') },
        },
      ],
    },
    { label: 'Projects', route: 'projects.index' },
    { label: 'Companies', route: 'companies.index' },
  ].filter((item) => {
    if (item.availableTo) {
      return (
        auth.user.roles.some((role: any) =>
          item.availableTo.includes(role.id)
        ) || item.availableTo.includes('*')
      );
    }
    return true;
  });

  let key = '';

  router.on('navigate', (event: any) => {
    key = event.detail.route;
  });
</script>

<div>
  <div class="min-h-screen">
    <Navbar {menu} {auth} />

    <!-- Page Heading -->
    {#if header}
      <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {@render header?.()}
        </div>
      </header>
    {/if}

    <!-- Page Content -->
    <main
      class="sm:p-6 py-2"
      transition:fade={{ duration: 400, easing: sineInOut }}
    >
      <Toast show={false} />
      {@render children?.()}
    </main>
  </div>
  <footer class="w-screen flex items-center justify-center h-12">
    Contact - <a href="/privacy">Privacy</a> - Terms
  </footer>
</div>
