<script lang="ts">
    import { writable } from "svelte/store";
    import { router, Link } from '@inertiajs/svelte';
    import GuestLayout from "../../Layouts/GuestLayout.svelte";
    import PrimaryButton from "../../Components/PrimaryButton.svelte";
    import InputLabel from "../../Components/InputLabel.svelte";

    let loading = false;

    // Create a store for form data
    const formData = writable({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    });

    let formErrors = {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    };

    // Function to handle form submission
    async function submit() {
        loading = true;
        router.post("/register", $formData);
    }
</script>
<svelte:head>
    <title>Register</title>
</svelte:head>

<GuestLayout>
    <h1 class="p-3 font-bold my-2 text-white text-xl">Register</h1>
    <form on:submit|preventDefault={submit}>
        <div>
            <InputLabel for="name">Name</InputLabel>
            <input
                id="name"
                type="text"
                class="mt-1 block w-full"
                bind:value={$formData.name}
                required
                autocomplete="name"
            />
            {#if formErrors.name}<span class="mt-2">{formErrors.name}</span>{/if}
        </div>

        <div class="mt-4"> 
            <InputLabel for="email">Email</InputLabel>
            <input
                id="email"
                type="email"
                bind:value={$formData.email}
                required
                autocomplete="username"
                class="mt-1 block w-full"
            />
            {#if formErrors.email}<span>{formErrors.email}</span>{/if}
        </div>

        <div class="mt-4">
            <InputLabel for="password">Password</InputLabel>
            <input
                id="password"
                type="password"
                bind:value={$formData.password}
                required
                autocomplete="new-password"
                class="mt-1 block w-full"
            />
            {#if formErrors.password}<span>{formErrors.password}</span>{/if}
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation">Confirm Password</InputLabel>
            <input
                id="password_confirmation"
                type="password"
                bind:value={$formData.password_confirmation}
                required
                autocomplete="new-password"
                class="mt-1 block w-full"
            />
            {#if formErrors.password_confirmation}<span>{formErrors.password_confirmation}</span>{/if}
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link href="/login"
                    class="underline text-sm text-gray-600 dark:text-gray-400 mx-3
                    hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 
                    focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Already registered?
            </Link>
            <PrimaryButton type="submit" disabled={loading}> 
                {loading ? "Loading..." : "Register"}
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
