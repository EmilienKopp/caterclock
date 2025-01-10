<script lang="ts">
    import { inertia, Link } from '@inertiajs/svelte';
    import route from '../../../vendor/tightenco/ziggy';
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import SecondaryButton from '$components/Buttons/SecondaryButton.svelte';
    import Clock from '$components/App/TimeLog/Clock.svelte';


    interface Props {
        ziggy: any;
        auth: {
        user: {
            name: string;
            email: string;
            profile_photo_url: string;
        };
    };
        canLogin: boolean;
        canRegister: boolean;
        laravelVersion: string;
        phpVersion: string;
    }

    let {
        ziggy,
        auth,
        canLogin,
        canRegister,
        laravelVersion,
        phpVersion
    }: Props = $props();

    let modal: HTMLDialogElement;

    console.log(auth);
</script>
<svelte:head>
    <title>Caterclock | Home</title>
</svelte:head>

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-primary"
    >
        {#if canLogin}
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-end">
            {#if auth.user}
            <Link
                href={route('dashboard')}
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Dashboard
            </Link>
            {:else}

                <Link
                    href={route('login')}
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Log in</Link
                >

                <Link
                    href={route('register')}
                    class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Register</Link
                >
            {/if}
        </div>
        {/if}

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex flex-col items-center justify-center my-24">
                <!-- <img src="/CATERCLOCK_WHITE.png" alt="Caterclock Logo"/> -->
                <h1 class="title text-4xl">caterclock</h1>
                <Clock />
                <h2 class="subtitle text-2xl">the minimalistic clock</h2>
                <h3>catering to your every need</h3>
            </div>
            <div class="flex justify-center items-center gap-8">
                <PrimaryButton href={route("register")} shape="wide">Register</PrimaryButton>
                <SecondaryButton href={route("login")} shape="wide">Login</SecondaryButton>
            </div>
        </div>
    </div>


<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
