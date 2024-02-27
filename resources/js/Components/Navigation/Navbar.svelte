<script lang="ts">
    import route from '$vendor/tightenco/ziggy';
    import { page, Link, inertia } from '@inertiajs/svelte';
    import dayjs from 'dayjs';
    import type { RouteItem } from '$types';
    import { now } from '$lib/stores';
    import ThemeSwitch from '$components/Buttons/ThemeSwitch.svelte';

    export let menu: RouteItem[] = [];
    const { auth } = $page.props;
    const roles: number[] | string[] = auth.user.roles;
    $: time = dayjs($now).format('HH:mm');
</script>
<nav
    class="navbar bg-base-100 border-b border-base-200 shadow-lg sticky top-0 z-50"
>
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    ><path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h8m-8 6h16"
                    /></svg
                >
            </div>
            <ul
                tabindex="-1"
                class="menu menu-sm dropdown-content mt-3 z-[9999] p-2 shadow bg-base-100 rounded-box w-52"
            >
                {#each menu as item}
                    <li>
                        {#if item.children?.length}
                            <details>
                                <summary>{item.label}</summary>
                                <ul class="p-2">
                                    {#each item.children as child}
                                        <li><Link href={route(child.route, item.params)}>{child.label}</Link></li>
                                    {/each}
                                </ul>
                            </details>
                        {:else}
                            <Link href={route(item.route, item.params)}>{item.label}</Link>
                        {/if}
                    </li>

                {/each}
            </ul>
        </div>
        <div class="flex items-center">
            <a class="btn btn-ghost text-xl" href="/">caterclock</a>
            <span class="hidden sm:inline-block">{time}</span>
        </div>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 items-center">
            {#each menu as item}
                <li>
                    
                    {#if item.children?.length}
                        <details>
                            <summary>{item.label}</summary>
                            <ul class="p-2">
                                {#each item.children as child}
                                    <li><Link href={route(child.route)}>{child.label}</Link></li>
                                {/each}
                            </ul>
                        </details>
                    {:else}
                        <Link href={route(item.route)}>{item.label}</Link>
                    {/if}
                </li>
            {/each}
        </ul>
    </div>
    <div class="navbar-end  flex items-center gap-2">
        <ThemeSwitch />
        <span class="text-sm hidden sm:inline-block">{auth?.user?.name ?? ""}</span>
        <div class="dropdown dropdown-end">
            <div
                tabindex="0"
                role="button"
                class="btn btn-ghost btn-circle avatar"
            >
                <div class="w-10 rounded-full">
                    <img title={auth.user.name} alt={auth.user.name} 
                        src="{auth.user.avatar}"
                    />
                </div>
            </div>
            <ul
                tabindex="-1"
                class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52"
            >
                <li>
                    <a class="justify-between" href="/profile">
                        Profile
                    </a>
                </li>
                <!-- <li><a>Settings</a></li> -->
                <li><button use:inertia={{href: "logout", method: "POST"}}>Logout</button></li>
            </ul>
        </div>
    </div>
</nav>
