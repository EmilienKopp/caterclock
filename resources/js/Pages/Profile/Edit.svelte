<!-- @migration-task Error while migrating Svelte code: `<h2>` cannot be a child of `<head>`. `<head>` only allows these children: `<base>`, `<basefont>`, `<bgsound>`, `<link>`, `<meta>`, `<title>`, `<noscript>`, `<noframes>`, `<style>`, `<script>`, `<template>`. The browser will 'repair' the HTML (by moving, removing, or inserting elements) which breaks Svelte's assumptions about the structure of your components.
https://svelte.dev/e/node_invalid_placement -->
<script>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.svelte';
import DeleteUserForm from './Partials/DeleteUserForm.svelte';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.svelte';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.svelte';

</script>

<AuthenticatedLayout>
    <head>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Profile</h2>
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <UpdateProfileInformationForm class="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </div>
</AuthenticatedLayout>

