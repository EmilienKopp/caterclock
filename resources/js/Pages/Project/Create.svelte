<script lang="ts">
    import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import SecondaryButton from '$components/Buttons/SecondaryButton.svelte';
    import PageTitle from '$components/UI/PageTitle.svelte';
    import InputLabel from '$components/Inputs/InputLabel.svelte';
    import TextInput from '$components/Inputs/TextInput.svelte';
    import { useForm, page } from '@inertiajs/svelte';
    import { toast } from '$lib/stores';
    import route from '$vendor/tightenco/ziggy';
    import Fieldset from '$components/Inputs/Fieldset.svelte';

    const {user} = $page.props.auth;

    const form = useForm('createProject',{
        name: '',
        description: '',
    });

    async function handleSubmit() {
        $form.post(route('projects.store'), {
            preserveScroll: true,
            onSuccess: () => {
                $form.reset();
                toast.success('Project created successfully!');
            },
            onError: () => {
                toast.error('There was a problem creating the project.');
                console.warn($form.errors);
            },
        });
    }
</script>

<AuthenticatedLayout>

    <div>

        <PageTitle headTitle="Create Project - FleaClock">
            Create Project
        </PageTitle>

        <form method="POST" on:submit|preventDefault={handleSubmit}>
            <input type="hidden" name="user_id" value={user.id} />
            <Fieldset>
                <legend>Basic Information</legend>
                <InputLabel for="name">
                    <span>Project Name</span>
                    <TextInput type="text" name="name" id="name" bind:value={$form.name} class="w-1/2" required />
                </InputLabel>
                <InputLabel for="description">
                    <span>Project Description</span>
                    <TextInput type="textarea" name="description" id="description" bind:value={$form.description} class="w-1/2"/>
                </InputLabel>
            </Fieldset>


            <PrimaryButton type="submit">Create</PrimaryButton>
            <SecondaryButton href={route('projects.index')}>Cancel</SecondaryButton>
        </form>

    </div>

</AuthenticatedLayout>