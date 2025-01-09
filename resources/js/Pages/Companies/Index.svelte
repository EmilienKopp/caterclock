<script lang="ts">
    import MiniButton from "$components/Buttons/MiniButton.svelte";
    import SecondaryButton from "$components/Buttons/SecondaryButton.svelte";
    import CompanyCard from "$components/Cards/CompanyCard.svelte";
    import InputLabel from "$components/Inputs/InputLabel.svelte";
    import TextInput from "$components/Inputs/TextInput.svelte";
    import Dialog from "$components/Modals/Dialog.svelte";
    import SimpleTable from "$components/Tables/SimpleTable.svelte";
    import TabItem from "$components/UI/TabItem.svelte";
    import TabLayout from "$components/UI/TabLayout.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import { queryParams, toast } from "$lib/stores";
    import { FilterService } from "$lib/utils/filter";
    import type { Company, ConnectionRequest, Project } from "$models";
    import route from "$vendor/tightenco/ziggy/src/js";
    import { router, useForm } from "@inertiajs/svelte";

    export let auth: any, roles: any, companies: Company[], ownedCompanies: Company[], managedCompanies: Company[], 
    employers: Company[], clients: Company[], projects: Project[], sentConnectionRequests: ConnectionRequest[], ziggy, errors;

    let activeTab: string = "Employers";
    let searchString = '';

    const modals = {
        addClient: false,
        addEmployer: false,
    }

    const clientForm = useForm({
        company_id: '',
        user_id: auth.user.id,
        role_id: roles.freelancer,
        sender_id: auth.user.id,
        code: ''
    })

    const employerForm = useForm({
        company_id: '',
        user_id: auth.user.id,
        sender_id: auth.user.id,
        role_id: roles.employee,
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
                console.log($employerForm.errors);
            }
        });
        modals.addEmployer = false;
    }

    async function addClient(id: number | string) {
        $clientForm.company_id = id;
        $clientForm.post(route('positions.store'), {
            preserveScroll: true,
            onSuccess: async () => {
                modals.addClient = false;
                await router.visit(route('companies.index', { tab: 'connections' }))
            },
            onError: () => {
                console.log($clientForm);
            }
        });
        modals.addClient = false;
    }

    async function handleWithdraw(requestItem: any) {
        await router.delete(route('connection-requests.destroy', requestItem.id), {
            preserveScroll: true,
            onSuccess: async () => {
                toast.success('Request withdrawn');
            },
            onError: () => {
                console.log('error');
            }
        });
    }

    $: tab = $queryParams?.tab ?? 'employers';
    
</script>

<AuthenticatedLayout>
    <h1 class="p-3 font-bold my-2 text-primary text-xl">Companies</h1>

    <TabLayout class="px-32">
        <TabItem title="employers" open={tab == 'employers'}>
            <div slot="head" class="w-full flex justify-end">
                <MiniButton on:click={() => modals.addEmployer = true } >
                    Add Employer
                </MiniButton>
            </div>
            {#if !employers.length}
                <div class="p-3 my-2 text-primary text-lg">
                    No employers registered yet
                </div>
            {:else}
                <SimpleTable data={employers} title="Employers"  
                    headers={[
                        { label: "Company", key: "name", route: "companies.show" },
                        { label: "Position", key: "position.position" },
                        { label: "Status", key: "status" },
                    ]} 
                />
            {/if}
        </TabItem>
        <TabItem title="clients" open={tab == 'clients'}>
            <div slot="head" class="w-full flex justify-end">
                <MiniButton on:click={() => modals.addClient = true } >
                    New Client
                </MiniButton>
            </div>
            {#if !clients.length}
                <div class="p-3 my-2 text-primary text-lg">
                    No clients registered yet
                </div>
            {:else}
                <SimpleTable data={clients} title="Clients"  
                    headers={[
                        { label: "Company", key: "name"},
                        { label: "Position", key: "position.role.name" },
                    ]}
                    popovers={{
                        "name": { component: CompanyCard, prop: "self"},
                    }}
                />
            {/if}
        </TabItem>
        <TabItem title="connection requests" open={tab == 'connections'}>
            {#if !sentConnectionRequests.length}
                <div class="p-3 my-2 text-primary text-lg">
                    No projects registered yet
                </div>
            {:else}
                <SimpleTable data={sentConnectionRequests} title="Connection Requests"  
                    popovers={{
                        "receiver.name": { component: CompanyCard, prop: "receiver" },
                        "company.name": { component: CompanyCard, prop: "company" },
                    }}
                    headers={[
                        { label: "To: User", key: "receiver.name" },
                        { label: "To: Company", key: "company.name", },
                        { label: "Status", key: "status", asBadge: true},
                        { label: "Position Requested", key: "role.name"}
                    ]} 
                    actions={[
                        { label: "Withdraw", action: (item) => handleWithdraw(item) }
                    ]}
                />
            {/if}
        </TabItem>
    </TabLayout>

    <Dialog title="Add Employer" bind:open={modals.addEmployer} >
        <div class="h-full flex flex-col gap-3">
            <div class="flex flex-col">
                <InputLabel for="name" >Search</InputLabel>
                <input type="text" id="name" bind:value={searchString} />
            </div>
            <ul>
                {#each companies.filter( (c) => FilterService.Fuzzy(c.name,searchString)) ?? [] as company}
                    <li class="p-3 my-2 text-primary text-lg flex justify-between w-full">
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
    <Dialog title="Add Client" bind:open={modals.addClient} >
        <input type="hidden" name="role_id" value={roles.freelancer} />
        <div class="h-full flex flex-col">
            <div class="flex flex-col">
                <label for="name">Search</label>
                <input type="text" id="name" bind:value={searchString} />
            </div>
            <ul>
                {#each companies.filter( (c) => FilterService.Fuzzy(c.name,searchString)) ?? [] as company}
                    <li class="p-3 my-2 text-primary text-lg flex justify-between w-full">
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


</AuthenticatedLayout>


<style>
    div.grid > div {
        @apply bg-gray-800 rounded-lg shadow-lg p-1;
    }
</style>