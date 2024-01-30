<script lang="ts">
    import SecondaryButton from "$components/Buttons/SecondaryButton.svelte";
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import route from "$vendor/tightenco/ziggy/src/js";
    import { useForm, router } from "@inertiajs/svelte";
    import Dialog from "$components/Dialog.svelte";
    import { FilterService } from "$lib/Filter";
    import MiniButton from "$components/MiniButton.svelte";
    import InputLabel from "$components/InputLabel.svelte";
    import TextInput from "$components/TextInput.svelte";
    import dayjs from "dayjs";

    export let auth, companies: any[], ownedCompanies: any[], managedCompanies: any[], employers: any[], clients: any[], projects: any[], sentConnectionRequests: any[], ziggy, errors;

    async function acceptRequest(request: any) {
        request.status = "accepted"; 
        await router.put(route("connection-requests.update", request), {
            preserveScroll: true,
            preserveState: true,
            onError: (errors: any) => {
                request.status = "pending";
                console.log(errors);
            }
        });
    }
</script>

<AuthenticatedLayout>
    <div>
        <h2 class="p-3 font-bold my-2 text-white text-lg">Employees</h2>
        <div class="grid grid-cols-2">
            {#each companies as company}
                <div class="p-2 border rounded pb-12">
                    <h3 class="font-bold">Employees @{company.name}</h3>
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