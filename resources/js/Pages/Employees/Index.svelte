<script lang="ts">
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import route from "$vendor/tightenco/ziggy/src/js";
    import { useForm, router } from "@inertiajs/svelte";
    import MiniButton from "$components/Buttons/MiniButton.svelte";
    import TextInput from "$components/Inputs/TextInput.svelte";
    import dayjs from "dayjs";
    import { Label, P } from "flowbite-svelte";

    export let auth, companies: any[], ownedCompanies: any[], managedCompanies: any[], employers: any[], clients: any[], projects: any[], sentConnectionRequests: any[], ziggy, errors;

    const form = useForm({
        code: ""
    });

    async function updateInviteCode(company: any) {
        $form = {...$form, ...company};
        console.log($form);
        $form.put(route("companies.update", company), {
            preserveScroll: true,
            preserveState: true,
            onError: (errors: any) => {
                console.log(errors);
            }
        });
    }

    async function acceptRequest(request: any) {
        const destination = route("connection-requests.update", {connectionRequest: request.id})
        await router.put(destination, {status: "accepted"},
            {
                preserveScroll: true,
                preserveState: true,
                onError: (errors: any) => {
                    console.log(errors);
                }
            }
        );
    }
</script>

<AuthenticatedLayout>
    <div>
        <h2 class="p-3 font-bold my-2 text-white text-lg">Employees</h2>
        <div class="grid grid-cols-2">
            {#each companies as company}
                <div class="p-2 border rounded pb-12">
                    <h3 class="font-bold">Employees @{company.name}</h3>
                    <div class="flex items-center gap-3">
                        <label for="code_{company.id}">Code:</label>
                        <TextInput name="code_{company.id}" bind:value={company.code} />
                        <PrimaryButton on:click={() => updateInviteCode(company)}>Save</PrimaryButton>
                    </div>
                    <ul>
                        {#each company.employees as employee}
                            <li>
                                {employee.name} 
                                {dayjs(employee.pivot.created_at).format("YYYY-MM-DD")}
                            </li>
                        {/each}
                    </ul>

                    <h3 class="mt-8 italic">Connection Requests</h3>
                    <ul>
                        {#each company.connection_requests as request}
                            <li>
                                {request.sender.name} 
                                {dayjs(request.created_at).format("YYYY-MM-DD")}
                                <MiniButton on:click={() => acceptRequest(request)}> Accept </MiniButton>
                            </li>
                        {/each}
                    </ul>
                </div>
            {/each}
        </div>
    </div>
</AuthenticatedLayout>