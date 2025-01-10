<script lang="ts">
    
    interface Props {
        data: any;
    }

    let { data }: Props = $props();
</script>


<div class="card">
    <div class="card-body">
        <h2 class="card-title">{data.name}</h2>
        <!-- <p class="card-subtitle text-gray-600">{data.address ?? ""}</p>
        <p class="card-subtitle text-gray-400">{data.contact_email ?? ""}</p> -->
        <dl>
            <dt class="text-gray-600">Address:</dt>
            <dd>{data.address ?? ""}</dd>
            <dt class="text-gray-600">Contact Email:</dt>
            <dd>{data.contact_email ?? ""}</dd>
        </dl>
        <div class="card-actions justify-end">
            <!-- <MiniButton href={route('companies.show', data.id)} class="mr-2">View</MiniButton> -->
        </div>
    </div>
</div>