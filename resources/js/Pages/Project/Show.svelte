<script lang="ts">
    import MiniButton from "$components/Buttons/MiniButton.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import TabLayout from "$components/UI/TabLayout.svelte";
    import TabItem from "$components/UI/TabItem.svelte";
    import DataList from "$components/UI/DataList.svelte";
    import { t } from "svelte-i18n";
    import SimpleBudgetChart from "$components/Charts/SimpleBudgetChart.svelte";
    
    
    interface Props {
        project: any;
        auth: any;
    }

    let { project, auth }: Props = $props();
</script>

<AuthenticatedLayout>
    <h1 class="w-full flex items-center justify-between">
        <div>
            {project.name}
            {#if project.company}
                @<strong>{project.company.name}</strong>
            {/if}
        </div>
        {#if project.user_id == auth.user.id || auth.user.companies.find(c => c.id == project.company_id)}
            <MiniButton href="/projects/{project.id}/edit">Edit</MiniButton>
        {/if}
    </h1>

    <TabLayout class="px-5" >
        <TabItem title="Overview" open>
            <DataList definition={{
                name: { label: "Name" },
                description: { label: "Description" },
                budget_low: { label: "Budget(low)", formatter: (value) => `JPY ${Math.round(value).toLocaleString()}` },
                budget_high: { label: "Budget(high)", formatter: (value) => `JPY ${Math.round(value).toLocaleString()}` },
                start_date: { label: "Start Date", formatter: (value) => new Date(value).toLocaleDateString()},
                end_date: { label: "End Date", formatter: (value) => new Date(value).toLocaleDateString()},
                spent: { label: "Spent", formatter: (value) => `JPY ${Math.round(value).toLocaleString()}` },
            }} data={project} />

            <SimpleBudgetChart budget={project.budget_low} spent={project.spent} title="Budget" />
        </TabItem>
        <TabItem title="Notes" >
            
        </TabItem>
        <TabItem title="Tasks" >

        </TabItem>
        <TabItem title="Reports" >

        </TabItem>
    </TabLayout>

</AuthenticatedLayout>