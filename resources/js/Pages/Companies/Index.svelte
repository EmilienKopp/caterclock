<script lang="ts">
    import SecondaryButton from "$components/Buttons/SecondaryButton.svelte";
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import route from "$vendor/tightenco/ziggy/src/js";
    import { useForm } from "@inertiajs/svelte";
    import Dialog from "$components/Dialog.svelte";
    import { FilterService } from "$lib/Filter";
    import MiniButton from "$components/MiniButton.svelte";
    import InputLabel from "$components/InputLabel.svelte";
    import TextInput from "$components/TextInput.svelte";

    export let auth, companies: any[], ownedCompanies: any[], managedCompanies: any[], 
    employers: any[], clients: any[], projects: any[], sentConnectionRequests: any[], ziggy, errors;

    let searchString = '';

    const modals = {
        addClient: false,
        addEmployer: false,
    }

    $: console.log(sentConnectionRequests);

    const clientForm = useForm({
        company_id: '',
        user_id: auth.user.id,
        position: 'hired_freelance'
    })

    const employerForm = useForm({
        company_id: '',
        user_id: auth.user.id,
        sender_id: auth.user.id,
        position: 'employee',
        code: ''
    })

    async function addEmployer(id: number | string) {
        $employerForm.company_id = id;
        $employerForm.post(route('positions.store'), {
            preserveScroll: true,
            onSuccess: () => {
                modals.addEmployer = false;
                console.log($employerForm);
            },
            onError: () => {
                console.log($employerForm);
            }
        });
        modals.addEmployer = false;
    }

    async function addClient(id: number | string) {
        $clientForm.company_id = id;
        $clientForm.post(route('positions.store'), {
            preserveScroll: true,
            onSuccess: () => {
                modals.addClient = false;
                console.log($clientForm);
            },
            onError: () => {
                console.log($clientForm);
            }
        });
        modals.addClient = false;
    }
</script>

<AuthenticatedLayout>
    <h1 class="p-3 font-bold my-2 text-white text-xl">Companies</h1>

    <div class="grid grid-cols-2 gap-5">

        <div>
            <h2 class="p-3 font-bold my-2 text-white text-lg">Employers</h2>
            <ul class="list-disc list-inside">
                {#each employers ?? [] as company}
                    <li class="p-3 my-2 text-white text-lg">
                        <a href={route('companies.show', company.id)}>{company.name}</a>
                    </li>
                {/each}
                {#if $employerForm.processing}
                    <li class="p-3 my-2 text-white text-lg flex gap-2 items-end">
                        Adding Employer<span class="loading loading-dots loading-sm"></span>
                    </li>
                {/if}
                {#each sentConnectionRequests as request}
                    <li class="p-3 my-2 text-white text-lg flex gap-2 items-end">
                        {request.sender.name} wants to join {request.company.name}
                    </li>
                {/each}
                <PrimaryButton on:click={() => modals.addEmployer = true } >
                    Add Employer
                </PrimaryButton>
                <Dialog title="Add Employer" bind:open={modals.addEmployer} >
                    <div class="h-full flex flex-col gap-3">
                        <div class="flex flex-col">
                            <InputLabel for="name" >Search</InputLabel>
                            <input type="text" id="name" bind:value={searchString} />
                        </div>
                        <ul>
                            {#each companies.filter( (c) => FilterService.Fuzzy(c.name,searchString)) ?? [] as company}
                                <li class="p-3 my-2 text-white text-lg flex justify-between w-full">
                                    <span>
                                        {@html FilterService.fuzzyHighlight(company.name, searchString)}
                                    </span>
                                    {#if company.is_public}
                                    <MiniButton on:click={() => addEmployer(company.id)} >
                                        Add
                                    </MiniButton>
                                    {:else}
                                    <div class="flex gap-2">
                                        <TextInput name="code"placeholder="Invite Code" bind:value={$employerForm.code} />
                                        <SecondaryButton on:click={() => addEmployer(company.id)} disabled={$employerForm.code != company.code} >
                                            Go!
                                        </SecondaryButton>
                                    </div>
                                    {/if}
                                </li>
                            {/each}
                        </ul>
                        
                        
                    </div>
                </Dialog>
            </ul>
        </div>

        <div>
            <h2 class="p-3 font-bold my-2 text-white text-lg">Clients</h2>
            <ul class="list-disc list-inside">
                {#each clients ?? [] as company}
                    <li class="p-3 my-2 text-white text-lg">
                        <a href={route('companies.show', company.id)}>{company.name}</a>
                    </li>
                {/each}
                {#if $clientForm.processing}
                    <li class="p-3 my-2 text-white text-lg flex gap-2 items-end">
                        Adding Client<span class="loading loading-dots loading-sm"></span>
                    </li>
                {/if}
                <PrimaryButton on:click={() => modals.addClient = true }>
                    Add Client
                </PrimaryButton>
                <Dialog title="Add Client" bind:open={modals.addClient} >
                    <div class="h-full flex flex-col">
                        <div class="flex flex-col">
                            <label for="name">Search</label>
                            <input type="text" id="name" bind:value={searchString} />
                        </div>
                        <ul>
                            {#each companies.filter( (c) => FilterService.Fuzzy(c.name,searchString)) ?? [] as company}
                                <li class="p-3 my-2 text-white text-lg flex justify-between w-full">
                                    <span>
                                        {@html FilterService.fuzzyHighlight(company.name, searchString)}
                                    </span>
                                    <MiniButton on:click={() => addClient(company.id)}>
                                        Add
                                    </MiniButton>
                                </li>
                            {/each}
                        </ul>
                    </div>
                </Dialog>
            </ul>
        </div>


    </div>
</AuthenticatedLayout>


<style>
    div.grid > div {
        @apply bg-gray-800 rounded-lg shadow-lg p-1;
    }
</style>