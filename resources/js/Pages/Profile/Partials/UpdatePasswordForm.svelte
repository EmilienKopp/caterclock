<script lang="ts">
import InputError from '$components/InputError.svelte';
import InputLabel from '$components/InputLabel.svelte';
import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
import TextInput from '$components/TextInput.svelte';
import route from '$vendor/tightenco/ziggy';
import { Link, page, router, useForm } from '@inertiajs/svelte';
import { toast } from '$lib/stores';

let passwordInput: any = null;
let currentPasswordInput: any = null;

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
})

const updatePassword = () => {
    const response = $form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            $form.reset();
            toast.show('Password updated.', 'success');
        },
        onError: () => {
            if ($form.errors.password) {
                $form.reset();
            }
            if ($form.errors.current_password) {
                $form.reset();
            }
        },
    });
};
</script>


<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Password</h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form on:submit|preventDefault={updatePassword} class="mt-6 space-y-6">
        <div>
            <InputLabel for="current_password" value="Current Password" />

            <TextInput
                id="current_password"
                bind:this={currentPasswordInput}
                bind:value={$form.current_password}
                type="password"
                class="mt-1 block w-full"
                autocomplete="current-password"
            />

            <InputError message={$form.errors.current_password} class="mt-2" />
        </div>

        <div>
            <InputLabel for="password" value="New Password" />

            <TextInput
                id="password"
                bind:this={passwordInput}
                bind:value={$form.password}
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <InputError message={$form.errors.password} class="mt-2" />
        </div>

        <div>
            <InputLabel for="password_confirmation" value="Confirm Password" />

            <TextInput
                id="password_confirmation"
                bind:value={$form.password_confirmation}
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <InputError message={$form.errors.password_confirmation} class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton disabled={$form.processing}>Save</PrimaryButton>

            {#if $form.recentlySuccessful}
                <p class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            {/if}
        </div>
    </form>
</section>

