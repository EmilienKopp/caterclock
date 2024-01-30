<script lang="ts">
    import GuestLayout from "../../Layouts/GuestLayout.svelte";
    import Checkbox from "$components/Checkbox.svelte";
    import InputError from "$components/InputError.svelte";
    import InputLabel from "$components/InputLabel.svelte";
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import TextInput from "$components/TextInput.svelte";
    import { Link, router } from "@inertiajs/svelte";
    import route from '../../../../vendor/tightenco/ziggy';

    export let canResetPassword: boolean = false;
    export let status: string | null = null;
    export let errors: any;

    let form: HTMLFormElement;
    let formData = {
        email: "",
        password: "",
        remember: false,
        processing: false,
    };
    

    const submit = () => {
        router.post(route("login"), formData);
    };
</script>

<GuestLayout>

    {#if status}
        <div class="mb-4 font-medium text-sm text-green-600">
            { status }
        </div>
    {/if}

    <form on:submit|preventDefault={submit} bind:this={form}>
        <div>
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
                bind:value={formData.email}
            />

            <InputError class="mt-2" message={errors?.email} />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Password" />

            <TextInput
                id="password"
                type="password"
                class="mt-1 block w-full"
                v-model="form.password"
                required
                autocomplete="current-password"
                bind:value={formData.password}
            />

            <InputError class="mt-2" message={form?.errors?.password} />
        </div>

        <div class="block mt-4">
            <label class="flex items-center" for="remember">
                <Checkbox name="remember" bind:checked={formData.remember} />
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
                    >Remember me</span
                >
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link href={route('register')}
            class="underline mx-3 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Register
            </Link>
            {#if canResetPassword}
                <Link
                    href={route('password.request')}
                    class="underline mx-3 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Forgot your password?
                </Link>
            {/if}

            <PrimaryButton disabled={formData?.processing} on:click={submit}>
                Log in
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
