<script lang="ts">

    import MiniToast from "$components/Feedback/MiniToast.svelte";
    import { Link, page } from "@inertiajs/svelte";
    import type { RouteItem } from "$types";
    import Navbar from "$components/Navigation/Navbar.svelte";
    import dayjs from "dayjs";

    const { auth } = $page.props;

    let showingNavigationDropdown = false;

    const menu: RouteItem[] = [
        { label: "Clock", route: "timelog.index" },
        { label: "Dashboard", route: "dashboard" },
        { label: "Employees", route: "employees.index" },
        { label: "Activity", route: "activities.index", children: [
            { label: "Montlhy", route: "activities.index" },
            // { label: "Today", route: route("activities.show",{date: dayjs().format("YYYY-MM-DD")}) },
        ] },
        { label: "Projects", route: "projects.index" },
        { label: "Companies", route: "companies.index" },
        { label: "Profile", route: "profile.edit" },
    ];
</script>

<div>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

        <Navbar {menu}/>

        <!-- Page Heading -->
        {#if $$slots.header}
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
        {/if}

        <!-- Page Content -->
        <main class="sm:p-6 p-0">
            <MiniToast show={false} />
            <slot />
        </main>
    </div>
</div>
