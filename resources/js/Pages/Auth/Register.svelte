<script lang="ts">
    import { writable } from "svelte/store";
    import { router, Link } from '@inertiajs/svelte';
    import GuestLayout from "../../Layouts/GuestLayout.svelte";
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import InputLabel from "$components/InputLabel.svelte";
    import TextInput from "$components/TextInput.svelte";
    import { useForm } from "@inertiajs/svelte";

    let loading = false;

    const form = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        register_type: "work",
        company_name: "",
    });

    export const snapshot = {
        capture: () => $form,
        restore: (data: typeof $form) => {
            form.set(data);
        },
    }

    // Function to handle form submission
    async function submit() {
        loading = true;
        $form.post("/register", {
            preserveScroll: true,
            onSuccess: () => {
                loading = false;
                router.push("/dashboard");
            },
            onError: () => {
                loading = false;
                console.log($form.errors);
            },
        });
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
            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                bind:value={$form.name}
                required
                autocomplete="name"
            />
            {#if $form.errors.name}<span class="mt-2">{$form.errors.name}</span>{/if}
        </div>

        <div class="mt-4"> 
            <InputLabel for="email">Email</InputLabel>
            <TextInput
                id="email"
                type="email"
                bind:value={$form.email}
                required
                autocomplete="username"
                class="mt-1 block w-full"
            />
            {#if $form.errors.email}<span>{$form.errors.email}</span>{/if}
        </div>

        <div class="mt-4">
            <InputLabel for="password">Password</InputLabel>
            <TextInput
                id="password"
                type="password"
                bind:value={$form.password}
                required
                autocomplete="new-password"
                class="mt-1 block w-full"
            />
            {#if $form.errors.password}<span>{$form.errors.password}</span>{/if}
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation">Confirm Password</InputLabel>
            <TextInput
                id="password_confirmation"
                type="password"
                bind:value={$form.password_confirmation}
                required
                autocomplete="new-password"
                class="mt-1 block w-full"
            />
            {#if $form.errors.password_confirmation}<span>{$form.errors.password_confirmation}</span>{/if}
        </div>

        <div class="mt-4 flex items-center">
            I am here to ... 
            <label class="inline-flex items-center ml-2">
                <input bind:group={$form.register_type} type="radio" class="radio" name="register_type" value="work" checked>
                <span class="ml-2">Work</span>
            </label>
            <label class="inline-flex items-center ml-2">
                <input bind:group={$form.register_type} type="radio" class="radio" name="register_type" value="hire">
                <span class="ml-2">Hire</span>
            </label>
            <label class="inline-flex items-center ml-2">
                <input bind:group={$form.register_type} type="radio" class="radio" name="register_type" value="both">
                <span class="ml-2">Both</span>
            </label>
        </div>

        {#if $form.register_type != "work"}
            <div class="mt-4">
                <InputLabel for="company_name">Company Name</InputLabel>
                <TextInput
                    name="company_name"
                    type="text"
                    bind:value={$form.company_name}
                    required
                    autocomplete="company_name"
                    class="mt-1 block w-full"
                />
                {#if $form.errors.company_name}<span>{$form.errors.company_name}</span>{/if}
            </div>
        {/if}

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
