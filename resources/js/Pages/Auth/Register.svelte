<script lang="ts">
    import { router, Link } from '@inertiajs/svelte';
    import GuestLayout from "$layouts/GuestLayout.svelte";
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import InputLabel from "$components/Inputs/InputLabel.svelte";
    import TextInput from "$components/Inputs/TextInput.svelte";
    import GoogleLoginButton from '$components/Buttons/GoogleLoginButton.svelte';
    import LineLoginButton from '$components/Buttons/LineLoginButton.svelte';
    import { useForm } from "@inertiajs/svelte";

    export let auth: any, oauth: any, session: any;


    let loading = false;

    const form = useForm({
        name: auth.oauth_user?.name || "",
        email: auth.oauth_user?.email || "",
        password: null,
        password_confirmation: null,
        register_type: "work",
        company_name: null,
        avatar: auth.oauth_user?.avatar || null,
        role_name: "employee",
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
        const route = oauth ? "/oauth/register" : "/register";
        $form.post(route, {
            preserveScroll: true,
            onSuccess: () => {
                loading = false;
            },
            onError: () => {
                loading = false;
                console.log($form.errors);
            },
        });
    }

    $: console.log($form);
</script>
<svelte:head>
    <title>Caterclock | Register</title>
</svelte:head>

<GuestLayout>
    <h1 class="p-3 font-bold my-2 text-primary text-xl">Register</h1>
    {#if auth.oauth_user?.name}
        <div class="p-3 my-2 bg-red-100 text-red-900 rounded-md">
            Welcome {auth.oauth_user.name}!
            Please fill in the form to complete your registration.
        </div>
    {/if}
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
            <InputLabel for="email">
                Email
                {#if oauth}
                    <span class="text-sm text-gray-500 dark:text-gray-400">(read-only)</span>
                {/if}
            </InputLabel>
            <TextInput
                id="email"
                type="email"
                bind:value={$form.email}
                required
                disabled={oauth}
                autocomplete="username"
                class="mt-1 block w-full"
            />
            {#if $form.errors.email}<span>{$form.errors.email}</span>{/if}
        </div>

        {#if !oauth}
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
        {/if}

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

        {#if $form.register_type == "work"}
            <div class="mt-4 flex items-center">
                as ...
                <label class="inline-flex items-center ml-2">
                    <input bind:group={$form.role_name} type="radio" class="radio" name="role_name" value="employee" checked>
                    <span class="ml-2">an employee</span>
                </label>
                <label class="inline-flex items-center ml-2">
                    <input bind:group={$form.role_name} type="radio" class="radio" name="role_name" value="freelancer">
                    <span class="ml-2">a freelancer</span>
                </label>
            </div>
        {:else}
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
        <div class="mt-6 grid grid-cols-2 gap-4">
            <GoogleLoginButton href={route("google.redirect")} />
            <LineLoginButton href={route("line.redirect")} />
        </div>
    </form>
</GuestLayout>
