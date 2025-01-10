<script lang="ts">
    import { preventDefault } from 'svelte/legacy';

    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import InputLabel from "$components/Inputs/InputLabel.svelte";
    import NumberInput from "$components/Inputs/NumberInput.svelte";
    import Select from "$components/Inputs/Select.svelte";
    import TextInput from "$components/Inputs/TextInput.svelte";
    import PageTitle from "$components/UI/PageTitle.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import { user } from "$lib/stores";
    import { toaster } from '$lib/utils/Toast';
    import route from '$vendor/tightenco/ziggy';
    import { useForm } from "@inertiajs/svelte";

    const { project } = $props();

    /** ProjectForm **/
    const form = useForm({
        ...project
    });

    function handleSubmit() {
        $form.patch(route('projects.update', project.id),{
            onError: (errors: any) => {
                console.log(errors);
            },

        });
    }

    /** Freelancer Rate Form **/
    const rateInfo = project.rates?.find( (r: any) => r.task_category_id == null);
    const freelancerRateForm = useForm({
        rate: rateInfo?.rate,
        currency: rateInfo?.currency
    });

    function handleFreelancerRateSubmit() {
        $freelancerRateForm.post(route('rates.upsert', {user: $user.id, project: project.id}),{
            onError: (errors: any) => {
                console.log(errors);
            },
            onSuccess: () => {
                toaster.success('Rate Updated');
            }
        });
    }

</script>

<AuthenticatedLayout>
    <PageTitle headTitle="Edit Project {$form.name}">Edit Project</PageTitle>
    <form class="grid grid-cols-2 gap-8" onsubmit={preventDefault(handleSubmit)}>
        <InputLabel value="Name">
            <TextInput required label="Name" bind:value={$form.name} name="name" placeholder="Name"/>
        </InputLabel>

        <InputLabel for="description" value="Description">
            <TextInput label="Description" bind:value={$form.description} name="description" placeholder="Description"/>
        </InputLabel>

        <div class="grid grid-cols-2 gap-3">
            <InputLabel value="Budget(low)">
                <NumberInput required label="Budget(low)" bind:value={$form.budget_low} name="budget_low" placeholder="Budget(low)"/>
            </InputLabel>
            
            <InputLabel for="budget_high" value="Budget(high)">
                <NumberInput label="Budget(high)" bind:value={$form.budget_high} name="budget_high" placeholder="Budget(high)"/>
            </InputLabel>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <InputLabel value="Start Date">
                <TextInput required type="date" label="Start Date" bind:value={$form.start_date} name="start_date" placeholder="Start Date"/>
            </InputLabel>
            
            <InputLabel for="end_date" value="End Date">
                <TextInput type="date" label="End Date" bind:value={$form.end_date} name="end_date" placeholder="End Date"/>
            </InputLabel>
        </div>

        <PrimaryButton type="submit">Save</PrimaryButton>
    </form>
    {#if $user.roles.some( r => r.name == 'freelancer')}
        <div class="divider"> Freelancer Space </div>
        <form class="grid grid-cols-2 gap-8" onsubmit={preventDefault(handleFreelancerRateSubmit)}>
            <InputLabel value="Rate">
                <NumberInput label="Rate" bind:value={$freelancerRateForm.rate} name="rate" placeholder="Rate"/>
            </InputLabel>
            <InputLabel for="currency" value="Currency">
                <Select bind:value={$freelancerRateForm.currency} name="currency" 
                    items={[{value:'JPY',label:'JPY'},{value:'USD',label:'USD'}]} />
            </InputLabel>
            <PrimaryButton type="submit">Save</PrimaryButton>
        </form>
    {/if}
</AuthenticatedLayout>