<script lang="ts">
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import SecondaryButton from '$components/Buttons/SecondaryButton.svelte';
    import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
    import Fieldset from '$components/Inputs/Fieldset.svelte';
    import InputLabel from '$components/Inputs/InputLabel.svelte';
    import TextInput from '$components/Inputs/TextInput.svelte';
    import PageTitle from '$components/UI/PageTitle.svelte';
    import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
    import { user } from '$lib/stores.svelte';
    import route from '$vendor/tightenco/ziggy';
    import { useForm } from '@inertiajs/svelte';


    const form = useForm('createProject',{
        name: '',
        description: '',
    });

    async function handleSubmit(e: Event) {
        e.preventDefault();
        $form.post(route('projects.store'), {
            preserveScroll: true,
            onSuccess: () => {
                $form.reset();
                toaster.success('Project created successfully!');
            },
            onError: () => {
                toaster.error('There was a problem creating the project.');
                console.warn($form.errors);
            },
        });
    }
</script>

<AuthenticatedLayout>

    <div>

        <PageTitle headTitle="Create Project - Caterclock">
            Create Project
        </PageTitle>

        <form method="POST" onsubmit={handleSubmit}>
            <input type="hidden" name="user_id" value={user.id} />
            <Fieldset>
                <legend>Basic Information</legend>
                <InputLabel for="name" value="Project Name">
                    <TextInput type="text" name="name" id="name" bind:value={$form.name} class="w-1/2" required />
                </InputLabel>
                <InputLabel for="description" class="flex flex-col" value="Description">
                    <TextInput type="textarea" name="description" id="description" bind:value={$form.description} class="w-1/2"/>
                </InputLabel>
            </Fieldset>


            <PrimaryButton type="submit">Create</PrimaryButton>
            <SecondaryButton href={route('projects.index')}>Cancel</SecondaryButton>
        </form>

    </div>

</AuthenticatedLayout>